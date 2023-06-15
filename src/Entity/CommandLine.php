<?php

namespace App\Entity;

use App\Repository\CommandLineRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Command; // Add the import statement
use App\Entity\Product; // Add the import statement


#[ORM\Entity(repositoryClass: CommandLineRepository::class)]
class CommandLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: "commandID")]
    private ?int $commandID = null;

    #[ORM\Column(name: "productID")]
    private ?int $productID = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $totalPrice = null;

    #[ORM\ManyToOne(targetEntity: Command::class)]
    #[ORM\JoinColumn(name: "commandID", referencedColumnName: "id")]
    private $command;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: "productID", referencedColumnName: "id")]
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommandID(): ?int
    {
        return $this->commandID;
    }

    public function setCommandID(int $commandID): static
    {
        $this->commandID = $commandID;

        return $this;
    }

    public function getProductID(): ?int
    {
        return $this->productID;
    }

    public function setProductID(int $productID): static
    {
        $this->productID = $productID;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    
       // Check if the product object is set
    if ($this->product) {
        // Calculate the total price based on the product's unit price and quantity
        $this->totalPrice = $this->product->getUnitPrice() * $quantity;
    }}

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }
    public function getCommand(): ?Command
    {
        return $this->command;
    }

    public function setCommand(?Command $command): static
    {
        $this->command = $command;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
