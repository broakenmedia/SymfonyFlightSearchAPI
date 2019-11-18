<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\UniqueConstraint;
/**
 * @ORM\Entity(repositoryClass="App\Repository\FlightRepository")
 * @ORM\Table(name="flight",
 * uniqueConstraints={
 *        @UniqueConstraint(name="flight_unique", 
 *            columns={"departure_airport_id", "arrival_airport_id", "aircraft_id", "departure_time"})})
 */
class Flight {
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
  /**
   * One Flight has One Departure Airport.
   * @ORM\ManyToOne(targetEntity="Airport")
   * @ORM\JoinColumn(name="departure_airport_id", referencedColumnName="id", unique=false)
   */
  private $departureAirport;
   /**
   * One Flight has One Departure Airport.
   * @ORM\ManyToOne(targetEntity="Airport")
   * @ORM\JoinColumn(name="arrival_airport_id", referencedColumnName="id", unique=false)
   */
  private $arrivalAirport;

     /**
   * One Flight has One Aircraft.
   * @ORM\ManyToOne(targetEntity="Aircraft")
   * @ORM\JoinColumn(name="aircraft_id", referencedColumnName="id", unique=false)
   */
  private $airCraft;

    /**
   * @ORM\Column(type="datetime", unique=false)
   * @Assert\NotBlank
   * 
   */
  private $departureTime;


  /**
   * @ORM\Column(type="decimal", precision=19, scale=4)
   * @Assert\NotBlank
   * 
   */
  private $seatCost;

  private $seatsRemaining;
  
  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  /**
   * @return mixed
   */
  public function getArrivalAirport()
  {
    return $this->arrivalAirport;
  }
  /**
   * @param mixed $arrivalAirport
   */
  public function setArrivalAirport($ap)
  {
    $this->arrivalAirport = $ap;
  }
  /**
   * @return mixed
   */
  public function getDepartureAirport()
  {
    return $this->departureAirport;
  }

  /**
   * @param mixed $departureAirport
   */
  public function setDepartureAirport($ap)
  {
    $this->departureAirport = $ap;
  }

  /**
   * @return mixed
   */
  public function getDepartureTime()
  {
    return $this->departureTime;
  }

  /**
   * @param mixed $departureTime
   */
  public function setDepartureTime($t)
  {
    $this->departureTime = $t;
  }

  public function getSeatCost()
  {
    return $this->seatCost;
  }

  public function setSeatCost($val)
  {
    $this->seatCost = $val;
  }

  public function getAirCraft()
  {
    return $this->airCraft;
  }

  public function setAirCraft($val)
  {
    $this->airCraft = $val;
  }

  public function getSeatsLeft()
  {
    return $this->seatsRemaining;
  }

  public function setSeatsLeft($val)
  {
    $this->seatsRemaining = $val;
  }

  public function getSeatsRemaining($bookings){
    $totalSeatsResult = $bookings->createQueryBuilder('bookings')
    ->select("sum(bookings.seatsBooked) as totalSeatsBooked")
    ->where("bookings.flightID = :flightid")
    ->groupBy('bookings.flightID')
    ->setParameter('flightid', $this->id)
    ->getQuery()->getResult();

    $totalSeatsBooked = (sizeof($totalSeatsResult) > 0 ? $totalSeatsResult[0]['totalSeatsBooked'] : 0);
    return $this->getAirCraft()->getSeatCount() - $totalSeatsBooked;
  }

}