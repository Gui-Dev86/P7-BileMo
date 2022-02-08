<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
<<<<<<< HEAD
use Symfony\Component\Serializer\Annotation\Groups;
=======
use OpenApi\Annotations as OA;
>>>>>>> Product

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @OA\Schema()
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
<<<<<<< HEAD
     * @Groups("post:read")
=======
     * @OA\Property(type="integer")
>>>>>>> Product
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
<<<<<<< HEAD
     * @Groups("post:read")
=======
     * @OA\Property(type="string")
>>>>>>> Product
     */
    private $name;

    /**
     * @ORM\Column(type="float")
<<<<<<< HEAD
     * @Groups("post:read")
=======
     * @OA\Property(type="float")
>>>>>>> Product
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=5000)
<<<<<<< HEAD
     * @Groups("post:read")
=======
     * @OA\Property(type="string")
>>>>>>> Product
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @OA\Property(type="string")
     */
    private $brand;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}
