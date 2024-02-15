<?php

declare(strict_types=1);
namespace Tests\Unit;

use \App\Services\{SalesTaxService, InvoiceService, PaymentGatewayService, EmailService};
use PHPUnit\Framework\TestCase;

class InvoiceServiceTest extends TestCase
{
    public function test_it_processes_invoice(): void
    {
        // test doubles
        $taxServiceMock = $this->createMock(SalesTaxService::class);
        $gatewayServiceMock = $this->createMock(PaymentGatewayService::class);
        $emailServiceMock = $this->createMock(EmailService::class);
        
        // any method in a (test double / mock) returns NULL by default
        // so we need to set the charge method in gateway services to return true
        // simulating that the api call took no time and returned true
        
        $gatewayServiceMock->method('charge')->willReturn(true);
        
        // given invoice service
        $service = new InvoiceService(
            $taxServiceMock,
            $gatewayServiceMock,
            $emailServiceMock
        );

        $customer = ['name' => 'Joe'];
        $amount = 100;

        // when process is called
        $result = $service->process($customer, $amount);

        // then invoice should be processed successfully
        $this->assertTrue($result);
    }
    
    public function test_it_sends_email_when_processing(): void
    {
        // test doubles
        $taxServiceMock = $this->createMock(SalesTaxService::class);
        $gatewayServiceMock = $this->createMock(PaymentGatewayService::class);
        $emailServiceMock = $this->createMock(EmailService::class);
        
        // any method in a (test double / mock) returns NULL by default
        // so we need to set the charge method in gateway services to return true
        // simulating that the api call took no time and returned true
        
        $gatewayServiceMock->method('charge')->willReturn(true);
        
        // given invoice service
        $service = new InvoiceService(
            $taxServiceMock,
            $gatewayServiceMock,
            $emailServiceMock
        );

        $customer = ['name' => 'Joe'];
        $amount = 100;

        $emailServiceMock
            ->expects($this->once())
            ->method('send');

        // when process is called
        $result = $service->process($customer, $amount);

        // then invoice should be processed successfully
        $this->assertTrue($result);
    }
}