<?php

declare(strict_types=1);
namespace Tests\Unit;

require __DIR__.'/../../vendor/autoload.php';

use \App\Router;
use \App\Exceptions\RouteNotFoundException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * assertEquals: == (loose comparison)
 * assertSame: === (strict comparison)
 */

class RouterTest extends TestCase
{
    private Router $router;

    public function setUp(): void
    {
        parent::setUp();
        $this->router = new Router();
    }

    // #[Test]
    public function test_that_it_registers_a_route(): void
    {
        // GIVEN that we have a router created

        // WHEN we register a new route
        $this->router->register('/', ["home", "index"]);

        // THEN the right route should be created
        $expected = [
            '/' => ["home", "index"]
        ];

        $this->assertSame($expected, $this->router->getRoutes());
    }

    public function test_that_no_routes_in_new_router(): void
    {
        // when a router is created
        $this->router = new Router();

        // then the routes array should be empty
        $this->assertEmpty($this->router->getRoutes());
    }
    
    #[testwith(['/remote'])] // route does not exist
    #[testwith(['/users'])] // route and action exist, but action class does not exist
    #[testwith(['/customers'])] // route and action and action class exist, but method of action class does not exist
    public function test_that_it_throws_route_not_found_exception(string $requestUri): void
    {
        // GIVEN that we have some routes registered
        $customer = new class{// anonymous class for testing purposes
            public function delete()
            {
                return 'deleted';
            }
        };
        
        $this->router->register('/users', ['user', 'create']);
        $this->router->register('/customers', [$customer::class, 'create']);
        
        // WHEN a route is resolved and there are errors in the route
        // we'll use a dataprovider for that
        $this->expectException(RouteNotFoundException::class);
        $this->router->resolve($requestUri);
        
        
        // THEN a RouteNotFoundException should be thrown
    }
    
    public function test_it_resolves_route_with_callable()
    {
        $this->router->register('/foo', fn() => [1, 2, 3]);
        
        $this->assertSame([1, 2, 3], $this->router->resolve('/foo'));
    }
    
    public function test_it_resolves_route_with_class()
    {
        $data = new class{// anonymous class for testing purposes
            public function getData()
            {
                return [1, 2, 3];
            }
        };

        $this->router->register('/foo', [$data::class, 'getData']);

        $this->assertSame([1, 2, 3], $this->router->resolve('/foo'));

    }

}