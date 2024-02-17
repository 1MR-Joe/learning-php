<?php

declare(strict_types=1);
namespace App\Entity;

use App\Enums\InvoiceStatus;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity()]
#[Table('invoice')]
class Invoice
{
    #[Id]
    #[Column(), GeneratedValue()]
    private int $id;
    // we need to mark the id column as an
    // auto incremeted / generated column
    
    #[Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private float $amount;

    #[column(name: 'invoice_number')]
    private string $invoiceNumber;
    
    #[column()]
    private InvoiceStatus $status;
    
    #[column(name: 'created_at')]
    private \DateTime $createdAt;

    #[OneToMany(targetEntity: InvoiceItem::class, mappedBy: 'invoice')]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }
    
    public function setId(int $id): Invoice
    {
        $this->id = $id;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): Invoice
    {
        $this->amount = $amount;
        return $this;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $number): Invoice
    {
        $this->invoiceNumber = $number;
        return $this;
    }

    public function getStatus(): InvoiceStatus
    {
        return $this->status;
    }

    public function setStatus(InvoiceStatus $status): Invoice
    {
        $this->status = $status;
        return $this;
    }

    public function getCreationTime(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreationTime(DateTime $time): Invoice
    {
        $this->createdAt = $time;
        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(InvoiceItem $item): Invoice
    {
        $this->items->add($item);
        return $this;
    }
}