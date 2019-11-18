<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Flight;
use App\Entity\Booking;
use App\Form\FlightType;
use App\Form\FlightUpdateType;
use App\Form\BookingType;

/**
 * Flight controller.
 * @Route("/api", name="api_")
 */
class FlightController extends FOSRestController
{
  /**
   * Lists all Flights.
   * @Rest\Get("/flights/all")
   *
   * @return Response
   */
  public function getFlightAction()
  {
    $repository = $this->getDoctrine()->getRepository(Flight::class);
    $flights = $repository->findall();
    return $this->handleView($this->view($flights));
  }

  /**
   * Search Flights.
   * @Rest\Get("/flights")
   *
   * @return Response
   */
  public function searchFlightAction(Request $request)
  {
    $repository = $this->getDoctrine()->getRepository(Flight::class);
    $departDate = $request->query->get('depart');
    if ($departDate){
        $departDate = date("Y-m-d", strtotime($departDate));
        $qb = $repository->whereDate(date_create_from_format("Y-m-d",$departDate));
        $departAirport = $request->query->get('airport');
        if($departAirport){
            $qb->leftJoin('e.departureAirport', 'ap')
            ->andWhere('ap.name = :airp')->setParameter('airp', $departAirport);
        }
    }else{
        return $this->handleView($this->view(["error"=>"'depart' date required."]));
    }
    $flights = $qb->getQuery()->getResult();
    $em = $this->getDoctrine()->getManager();
    for($i = 0; $i < sizeof($flights); $i++){
        $flights[$i]->setSeatsLeft($flights[$i]->getSeatsRemaining($em->getRepository(Booking::class)));
    }
    return $this->handleView($this->view($flights));
  }

  /**
   * Create booking.
   * @Rest\Post("/flight/{flightid}/book")
   *
   * @return Response
   */
  public function bookFlightAction(int $flightid, Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $flight = $em->getRepository(Flight::class)->find($flightid);
    $bookings = $em->getRepository(Booking::class);
    $totalSeatsResult = $bookings->createQueryBuilder('bookings')
    ->select("sum(bookings.seatsBooked) as totalSeatsBooked")
    ->where("bookings.flightID = :flightid")
    ->groupBy('bookings.flightID')
    ->setParameter('flightid', $flightid)
    ->getQuery()->getResult();

    $totalSeatsBooked = (sizeof($totalSeatsResult) > 0 ? $totalSeatsResult[0]['totalSeatsBooked'] : 0);

    $booking = new Booking();
    $booking->setFlightID($flight);
    $form = $this->createForm(BookingType::class, $booking);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    
    if($totalSeatsBooked >= $flight->getAircraft()->getSeatCount()){
        return $this->handleView($this->view(["error"=>"There are no more seats available."]));
    }else if($totalSeatsBooked + $form->get('seatsBooked')->getData() > $flight->getAircraft()->getSeatCount()){
        return $this->handleView($this->view(["error"=>"There are not enough seats left for a booking this size."]));
    }

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($booking);
      $em->flush();
      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }

    return $this->handleView($this->view($form->getErrors()));
  }

  /**
   * Create Flight.
   * @Rest\Post("/flight")
   *
   * @return Response
   */
  public function postFlightAction(Request $request)
  {
    $flight = new Flight();
    $form = $this->createForm(FlightType::class, $flight);
    $data = json_decode($request->getContent(), true);
    
    $form->submit($data);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($flight);
      $em->flush();
      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }
    return $this->handleView($this->view($form->getErrors()));
  }

  /**
   * Update Flight.
   * @Rest\Put("/flight/{flightid}")
   *
   * @return Response
   */
  public function putFlightAction(int $flightid, Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $flight = $em->getRepository(Flight::class)->find($flightid);
    $form = $this->createForm(FlightType::class, $flight);
    $data = json_decode($request->getContent(), true);
    $form->submit($data, false);
    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->flush();
      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_OK));
    }
    return $this->handleView($this->view($form->getErrors()));
  }
}