<?php

declare(strict_types=1);
namespace App\Attributes;

use App\Enums\HttpMethod;
use Attribute;

#[Attribute()]
class Route
{
    public function __construct(public string $path, public HttpMethod $requestMethod = HttpMethod::GET)
    {
    }
}