<?php

declare(strict_types=1);
namespace App\Enums;

enum HttpMethod: string
{
    case POST = 'post';
    case GET = 'get';
    case HEAD = 'head';
    case PUT = 'put';
    
}