<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\UniqueConstraint;
/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="booking")
 */
class Booking {
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity="Flight")
   * @ORM\JoinColumn(name="flight_id", referencedColumnName="id", unique=false)
   */
  private $flightID;

  /**
   * @ORM\Column(type="string")
   * @Assert\NotBlank
   */
  private $firstName;

    /**
   * @ORM\Column(type="string")
   * @Assert\NotBlank
   */
  private $lastName;

  /**
   * @ORM\Column(type="integer")
   * @Assert\NotBlank
   */
  private $seatsBooked = 1;

  /**
   * @ORM\Column(type="datetime")
   */
  protected $created;

  /**
   * @ORM\Column(type="datetime", nullable = true)
   */
  protected $updated;
  
  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getFlightID()
  {
    return $this->flightID;
  }

  public function setFlightID($val)
  {
    $this->flightID = $val;
  }

  public function getFirstName()
  {
    return $this->firstName;
  }

  public function setFirstName($val)
  {
    $this->firstName = $val;
  }

  public function getLastName()
  {
    return $this->lastName;
  }

  public function setLastName($val)
  {
    $this->lastName = $val;
  }

  public function getSeatsBooked()
  {
    return $this->seatsBooked;
  }

  public function setSeatsBooked($val)
  {
    $this->seatsBooked = $val;
  }

  /**
   * Gets triggered only on insert
   * @ORM\PrePersist
   */
  public function onPrePersist()
  {
      $this->created = new \DateTime("now");
      $this->updated = new \DateTime("now");
  }

  /**
   * Gets triggered every time on update
   * @ORM\PreUpdate
   */
  public function onPreUpdate()
  {
      $this->updated = new \DateTime("now");
  }

}