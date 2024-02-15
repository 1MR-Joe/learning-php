<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Attributes\Route;
use App\Container;
use App\Services\InvoiceService;
use App\View;

class HomeController
{
    public function __construct(private InvoiceService $invoiceService)
    {
        
    }

    #[Route('/')]
    public function index(): View
    {   
        return View::make('index');
    }
}
