<?php

namespace App\Entity;

use App\Repository\CarPhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'car_photos')]
#[ORM\Entity(repositoryClass: CarPhotoRepository::class)]
class CarPhoto
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Car::class, inversedBy: 'carPhotos')]
    #[ORM\JoinColumn(name: 'car_id', referencedColumnName: 'id')]
    private $car;

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
     * Set car
     *
     * @param \App\Entity\Car $car
     *
     * @return CarPhoto
     */
    public function setCar(?Car $car = null)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * Get car
     *
     * @return \App\Entity\Car
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return CarPhoto
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
