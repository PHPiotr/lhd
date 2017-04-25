<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="cars")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CarRepository")
 */
class Car
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @ORM\Column(name="is_reserved", type="boolean")
     */
    private $isReserved = false;

    /**
     * @ORM\Column(name="is_sold", type="boolean")
     */
    private $isSold = false;

    /**
     * @ORM\Column(name="is_coming_soon", type="boolean")
     */
    private $isComingSoon = false;

    /**
     * @ORM\OneToMany(targetEntity="CarPhoto", mappedBy="car")
     */
    private $carPhotos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mainPhotoId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $make;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $variant;

    /**
     * @ORM\Column(name="first_registration", type="string", length=10)
     * @Assert\NotBlank()
     */
    private $firstRegistration;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $mileage;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     */
    private $mot;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $doors;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $seats;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $colour;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $body;

    /**
     * @ORM\Column(name="fuel_type", type="string", length=255, nullable=true)
     */
    private $fuelType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $transmission;

    /**
     * @ORM\Column(name="engine_size", type="decimal", scale=2, nullable=true)
     */
    private $engineSize;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bhp;

    /**
     * @ORM\Column(name="mpg_urban", type="decimal", scale=2, nullable=true)
     */
    private $mpgUrban;

    /**
     * @ORM\Column(name="mpg_extra_urban", type="decimal", scale=2, nullable=true)
     */
    private $mpgExtraUrban;

    /**
     * @ORM\Column(name="mpg_combined", type="decimal", scale=2, nullable=true)
     */
    private $mpgCombined;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $length;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(name="country_of_origin", type="string", length=255, nullable=true)
     */
    private $countryOfOrigin;

    public function __construct()
    {
        $this->carPhotos = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Car
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Car
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Car
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set isReserved
     *
     * @param boolean $isReserved
     *
     * @return Car
     */
    public function setIsReserved($isReserved)
    {
        $this->isReserved = $isReserved;

        return $this;
    }

    /**
     * Get isReserved
     *
     * @return boolean
     */
    public function getIsReserved()
    {
        return $this->isReserved;
    }

    /**
     * Set isSold
     *
     * @param boolean $isSold
     *
     * @return Car
     */
    public function setIsSold($isSold)
    {
        $this->isSold = $isSold;

        return $this;
    }

    /**
     * Get isSold
     *
     * @return boolean
     */
    public function getIsSold()
    {
        return $this->isSold;
    }

    /**
     * Set make
     *
     * @param string $make
     *
     * @return Car
     */
    public function setMake($make)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * Get make
     *
     * @return string
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return Car
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set variant
     *
     * @param string $variant
     *
     * @return Car
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;

        return $this;
    }

    /**
     * Get variant
     *
     * @return string
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * Set firstRegistration
     *
     * @param string $firstRegistration
     *
     * @return Car
     */
    public function setFirstRegistration($firstRegistration)
    {
        $this->firstRegistration = $firstRegistration;

        return $this;
    }

    /**
     * Get firstRegistration
     *
     * @return string
     */
    public function getFirstRegistration()
    {
        return $this->firstRegistration;
    }

    /**
     * Set mileage
     *
     * @param integer $mileage
     *
     * @return Car
     */
    public function setMileage($mileage)
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * Get mileage
     *
     * @return integer
     */
    public function getMileage()
    {
        return $this->mileage;
    }

    /**
     * Set mot
     *
     * @param integer $mot
     *
     * @return Car
     */
    public function setMot($mot)
    {
        $this->mot = $mot;

        return $this;
    }

    /**
     * Get mot
     *
     * @return integer
     */
    public function getMot()
    {
        return $this->mot;
    }

    /**
     * Set doors
     *
     * @param integer $doors
     *
     * @return Car
     */
    public function setDoors($doors)
    {
        $this->doors = $doors;

        return $this;
    }

    /**
     * Get doors
     *
     * @return integer
     */
    public function getDoors()
    {
        return $this->doors;
    }

    /**
     * Set seats
     *
     * @param integer $seats
     *
     * @return Car
     */
    public function setSeats($seats)
    {
        $this->seats = $seats;

        return $this;
    }

    /**
     * Get seats
     *
     * @return integer
     */
    public function getSeats()
    {
        return $this->seats;
    }

    /**
     * Set colour
     *
     * @param string $colour
     *
     * @return Car
     */
    public function setColour($colour)
    {
        $this->colour = $colour;

        return $this;
    }

    /**
     * Get colour
     *
     * @return string
     */
    public function getColour()
    {
        return $this->colour;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Car
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set fuelType
     *
     * @param string $fuelType
     *
     * @return Car
     */
    public function setFuelType($fuelType)
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    /**
     * Get fuelType
     *
     * @return string
     */
    public function getFuelType()
    {
        return $this->fuelType;
    }

    /**
     * Set transmission
     *
     * @param string $transmission
     *
     * @return Car
     */
    public function setTransmission($transmission)
    {
        $this->transmission = $transmission;

        return $this;
    }

    /**
     * Get transmission
     *
     * @return string
     */
    public function getTransmission()
    {
        return $this->transmission;
    }

    /**
     * Set engineSize
     *
     * @param string $engineSize
     *
     * @return Car
     */
    public function setEngineSize($engineSize)
    {
        $this->engineSize = $engineSize;

        return $this;
    }

    /**
     * Get engineSize
     *
     * @return string
     */
    public function getEngineSize()
    {
        return $this->engineSize;
    }

    /**
     * Set bhp
     *
     * @param integer $bhp
     *
     * @return Car
     */
    public function setBhp($bhp)
    {
        $this->bhp = $bhp;

        return $this;
    }

    /**
     * Get bhp
     *
     * @return integer
     */
    public function getBhp()
    {
        return $this->bhp;
    }

    /**
     * Set mpgUrban
     *
     * @param string $mpgUrban
     *
     * @return Car
     */
    public function setMpgUrban($mpgUrban)
    {
        $this->mpgUrban = $mpgUrban;

        return $this;
    }

    /**
     * Get mpgUrban
     *
     * @return string
     */
    public function getMpgUrban()
    {
        return $this->mpgUrban;
    }

    /**
     * Set mpgExtraUrban
     *
     * @param string $mpgExtraUrban
     *
     * @return Car
     */
    public function setMpgExtraUrban($mpgExtraUrban)
    {
        $this->mpgExtraUrban = $mpgExtraUrban;

        return $this;
    }

    /**
     * Get mpgExtraUrban
     *
     * @return string
     */
    public function getMpgExtraUrban()
    {
        return $this->mpgExtraUrban;
    }

    /**
     * Set mpgCombined
     *
     * @param string $mpgCombined
     *
     * @return Car
     */
    public function setMpgCombined($mpgCombined)
    {
        $this->mpgCombined = $mpgCombined;

        return $this;
    }

    /**
     * Get mpgCombined
     *
     * @return string
     */
    public function getMpgCombined()
    {
        return $this->mpgCombined;
    }

    /**
     * Set length
     *
     * @param integer $length
     *
     * @return Car
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set width
     *
     * @param integer $width
     *
     * @return Car
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Car
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set countryOfOrigin
     *
     * @param string $countryOfOrigin
     *
     * @return Car
     */
    public function setCountryOfOrigin($countryOfOrigin)
    {
        $this->countryOfOrigin = $countryOfOrigin;

        return $this;
    }

    /**
     * Get countryOfOrigin
     *
     * @return string
     */
    public function getCountryOfOrigin()
    {
        return $this->countryOfOrigin;
    }

    /**
     * Get carPhotos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarPhotos()
    {
        return $this->carPhotos;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Car
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add carPhoto
     *
     * @param \AppBundle\Entity\CarPhoto $carPhoto
     *
     * @return Car
     */
    public function addCarPhoto(\AppBundle\Entity\CarPhoto $carPhoto)
    {
        $this->carPhotos[] = $carPhoto;

        return $this;
    }

    /**
     * Remove carPhoto
     *
     * @param \AppBundle\Entity\CarPhoto $carPhoto
     */
    public function removeCarPhoto(\AppBundle\Entity\CarPhoto $carPhoto)
    {
        $this->carPhotos->removeElement($carPhoto);
    }

    /**
     * Set mainPhotoId
     *
     * @param integer $mainPhotoId
     *
     * @return Car
     */
    public function setMainPhotoId($mainPhotoId)
    {
        $this->mainPhotoId = $mainPhotoId;

        return $this;
    }

    /**
     * Get mainPhotoId
     *
     * @return integer
     */
    public function getMainPhotoId()
    {
        return $this->mainPhotoId;
    }

    public function getValueForKey($k)
    {
        return !empty($this->$k) ? $this->$k : null;
    }

    /**
     * Set isComingSoon
     *
     * @param boolean $isComingSoon
     *
     * @return Car
     */
    public function setIsComingSoon($isComingSoon)
    {
        $this->isComingSoon = $isComingSoon;

        return $this;
    }

    /**
     * Get isComingSoon
     *
     * @return boolean
     */
    public function getIsComingSoon()
    {
        return $this->isComingSoon;
    }
}
