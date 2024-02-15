<?php

declare(strict_types=1);
namespace App\Controllers;

class ExampleGenerator
{
    public function __construct()
    {
        
    }

    public function index()
    {
        $numbers = range(1, 30000000);

        echo "<pre>";
        print_r($numbers);
        echo "</pre>";
    }
}