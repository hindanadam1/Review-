<?php
namespace App\Services;

class NewsletterService
{
    public function subscribe(?string $email): array
    {
        $email = trim((string) $email);

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Email invalide pour la newsletter.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Merci pour votre inscription a la newsletter.',
        ];
    }
}
