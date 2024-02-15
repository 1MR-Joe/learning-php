<?php

declare(strict_types=1);
namespace App\Models;

use App\Enums\EmailStatus;
use App\Model;
use Symfony\Component\Mime\Address;
use PDO;
use PDOStatement;

class EmailModel extends Model
{
    public function queue
    (
        string $from,
        string $to,
        string $subject,
        string $html,
        ?string $text = null,
    ):void {
        // make prepared stmt
        $stmt = $this->db->prepare(
            'INSERT INTO emails(`from`, `to`, `subject`, text_body, html_body, `status`, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())'
        );

        // execute stmt with parameters in
        $stmt->execute([$from, $to, $subject, $text, $html, EmailStatus::Queue->value]);
    }

    public function getEmailsByStatus(EmailStatus $status): array
    {
        $stmt = $this->db->prepare(
            'SELECT *
             FROM emails
             WHERE status = ?'
        );

        $stmt->execute([$status->value]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function markEmailSent(int $id): void
    {
        $stmt = $this->db->prepare(
            'UPDATE emails
             SET status = ?, sent_at = NOW()
             WHERE id = ?'
        );

        $stmt->execute([EmailStatus::Sent->value, $id]);
    }
}