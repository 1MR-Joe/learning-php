<?php

declare(strict_types=1);
namespace App;

use App\Exceptions\Container\ContainerException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function get(string $id)
    {
        // echo "<pre>";
        // print_r($this->getEntries());
        // echo "</pre>";
        
        // echo "searching for entry {$id}";echo "<br/>";
        if($this->has($id))
        {
            // echo "found entry {$id}";echo "<br/>";
            $entry = $this->entries[$id];

            if(is_callable($entry)) return $entry($this);

            $id = $entry;
        }
        // echo "didn't find entry {$id}";echo "<br/>";
        return $this->resolve($id);
    }

    public function set(string $id, callable|string $concrete)
    {
        if(! isset($this->entries[$id]))
        {
            $this->entries[$id] = $concrete;
        }
    }

    public function resolve(string $id): ?object
    {
        // inspect the class
        $reflectionClass = new \ReflectionClass($id);

        if(! $reflectionClass->isInstantiable())
        {
            throw new ContainerException("class {$id} not instantiable");
        }

        // inspect the constructor
        $constructor = $reflectionClass->getConstructor();
        
        if(! $constructor)
        {
            return new $id;
        }

        // inspect the parameters (dependencies)
        $params = $constructor->getParameters();

        if(! $params)
        {
            return new $id;
        }


        // if the constructor parameter is a class, resolve it using the container
        $dependencies = array_map(
            function(\ReflectionParameter $param) use ($id)
            {
                $name = $param->getName();
                $type = $param->getType();

                if(! $type){
                    throw new ContainerException(
                    "can't resolve class {$id}, parameter {$name} need to be type hinted."
                    );
                }
                
                if($type instanceof \ReflectionUnionType){
                    throw new ContainerException(
                        "can't resolve class {$id}, parameter {$name} is of union type."
                    );
                }

                if($type instanceof \ReflectionNamedType && !$type->isBuiltin())
                {
                    return $this->get($type->getName());
                }

                throw new ContainerException(
                    "can't resolve class {$id}, parameter {$name} is an invalid parameter"
                );
            }, 
            $params
        );

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function getEntries()
    {
        return $this->entries;
    }
}
