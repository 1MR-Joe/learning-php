<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\CustomMailer;
use App\View;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class UserController
{
    public function __construct(protected MailerInterface $mailer)
    {
    }

    #[Get('/users/create')]
    public function create(): View
    {
        return View::make('users/register');
    }

    #[Post('/users')]
    public function register()
    {
        $name      = $_POST['name'];
        $email     = $_POST['email'];
        $firstName = explode(' ', $name)[0];

        $text = <<<Body
        Hello $firstName,

        Thank you for signing up!
        Body;

        $html = <<<HTMLBody
        <h1 style="text-align: center; color: blue;">Welcome</h1>
        Hello $firstName,
        <br/><br/>
        Thank you for signing up!
        HTMLBody;

        (new \App\Models\Email)->queue(
            "support@example.com",
            $email,
            "Welcome!",
            $html,
            $text
        );

        // $email = (new Email())
        //     ->from('support@example.com')
        //     ->to($email)
        //     ->subject('Welcome!')
        //     ->text($text)
        //     ->html($html);

        // DSN == data source name
        // it's a string that represnts a remote location
        // $this->mailer->send($email);
    }
}