<?php

namespace App\Exceptions;

class ViewNotFoundException extends \Exception
{
    protected $message = "view not found";
}