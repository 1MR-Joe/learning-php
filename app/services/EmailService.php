<?php

declare(strict_types = 1);

namespace App\Services;

use App\Enums\EmailStatus;
use App\Models\EmailModel;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    public function __construct(protected EmailModel $emailModel, protected MailerInterface $mailer)
    {
    }

    public function sendQueuedEmails(): void
    {
        $emails = $this->emailModel->getEmailsByStatus(EmailStatus::Queue);

        foreach($emails as $e)
        {
            $emailMessage = (new Email())
            ->from($e->from)
            ->to($e->to)
            ->subject($e->subject)
            ->html($e->html_body)
            ->text($e->text_body);

            $this->mailer->send($emailMessage);

            $this->emailModel->markEmailSent($e->id);
        }
    }
}
