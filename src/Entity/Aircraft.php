<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\UniqueConstraint;
/**
 * @ORM\Entity
 * @ORM\Table(name="aircraft")
 */
class Aircraft {
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="string")
   * @Assert\NotBlank
   */
  private $owner;

  /**
   * @ORM\Column(type="string")
   * @Assert\NotBlank
   */
  private $manafacturer;

  /**
   * @ORM\Column(type="string")
   * @Assert\NotBlank
   */
  private $model;

  /**
   * @ORM\Column(type="string", unique=true)
   * @Assert\NotBlank
   */
  private $tailNumber;

    /**
   * @ORM\Column(type="integer")
   * 
   */
  private $seatCount = 260;
  
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

  public function getTailNumber()
  {
    return $this->tailNumber;
  }

  public function setTailNumber($val)
  {
    $this->tailNumber = $val;
  }

  public function getSeatCount()
  {
    return $this->seatCount;
  }

  public function setSeatCount($val)
  {
    $this->seatCount = $val;
  }

  public function getOwner()
  {
    return $this->owner;
  }

  public function setOwner($val)
  {
    $this->owner = $val;
  }

  public function getManafacturer()
  {
    return $this->manafacturer;
  }

  public function setManafacturer($val)
  {
    $this->manafacturer = $val;
  }

  public function getModel()
  {
    return $this->model;
  }

  public function setModel($val)
  {
    $this->model = $val;
  }

}