<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Enums\InvoiceStatus;
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$items = [['item 1', 1, 15], ['item 2', 2, 7.5], ['item 3', 3, '3.5']];

$invoice = (new Invoice())
    ->setAmount(45)
    ->setInvoiceNumber('1')
    ->setStatus(InvoiceStatus::Pending)
    ->setCreationTime(new DateTime());

foreach($items as [$description, $quantity, $unitPrice])
{
    $item = (new InvoiceItem())
        ->setDescription($description)
        ->setQuantity($quantity)
        ->setUnitPrice($unitPrice);

    $invoice->addItem($item);
}

