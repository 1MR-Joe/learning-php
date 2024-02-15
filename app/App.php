<?php

declare(strict_types = 1);

namespace App;

use App\Exceptions\RouteNotFoundException;
use App\Services\PaymentGatewayService;
use App\Services\PaymentGatewayServiceInterface;
use Symfony\Component\Mailer\MailerInterface;
use Dotenv\Dotenv;

class App
{
    private static DB $db;
    private Config $config;

    public function __construct(
        protected Container $container,
        protected ?Router $router = null,
        protected array $request = [],
        ) {
        }
        
        public static function db(): DB
        {
            return static::$db;
        }
        
        public function boot(): static
        {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
            $dotenv->load();
            
            $this->config = new Config($_ENV);
            static::$db = new DB($this->config->db ?? []);
            
            $this->container->set(PaymentGatewayServiceInterface::class, PaymentGatewayService::class);
            $this->container->set(MailerInterface::class, fn() => new CustomMailer($this->config->mailer['dsn']));
            
            return $this;
        }

        public function run()
        {
            try {
                echo $this->router->resolve(strtolower($this->request['method']), $this->request['uri']);
            } catch (RouteNotFoundException $e) {
                http_response_code(404);
                echo $e->getMessage();echo "<br/>";
                echo View::make('error/404');
            }
        }
        
    }
