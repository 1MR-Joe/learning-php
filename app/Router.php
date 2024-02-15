<?php

declare(strict_types=1);
namespace APP;

use App\Attributes\Route;
use App\Container;
use App\Exceptions\RouteNotFoundException;
use ReflectionAttribute;
use ReflectionClass;

class Router
{
    protected array $routes = [];

    public function __construct(private Container $container)
    {
        
    }

    public function registerRouteFromControllerAttributes(array $controllers)
    {
        foreach($controllers as $controller)
        {
            $reflectionController = new ReflectionClass($controller);

            foreach($reflectionController->getMethods() as $method)
            {
                $attributes = $method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);

                foreach($attributes as $attr)
                {
                    $route = $attr->newInstance();
                    $this->register($route->requestMethod->value, $route->path, [$controller, $method->getName()]);
                }
            }
        }
    }

    public function register(string $requestMethod, string $route, callable|array $action): self
    {
        $this->routes[$requestMethod][$route] = $action;
        return $this;
    }

    public function resolve(string $requestMethod, string $requestUri)
    {
        $route = explode('?', $requestUri)[0];
        $action = $this->routes[$requestMethod][$route] ?? null;

        if(! $action)
        {
            throw new RouteNotFoundException('action not found');
        }

        if(is_callable($action))
        {
            return call_user_func($action);
        }

        if(is_array($action))
        {
            [$class, $method] = $action;
            
            if(class_exists($class))
            {
                $class = $this->container->get($class);
                if(method_exists($class, $method))
                {
                    return call_user_func_array([$class, $method], []);
                }
                else
                throw new RouteNotFoundException("class method not found");
            }
            else
            throw new RouteNotFoundException("class not found");
        }
        // if all conditions are not satisfied
        throw new RouteNotFoundException("can not process route: {$requestUri}");
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}