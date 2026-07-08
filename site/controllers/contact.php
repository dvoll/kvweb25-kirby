<?php

use dvll\Sitepackage\Helpers\Helper;
use Kirby\Toolkit\Str;

return function ($kirby, $pages, $page) {

    $alert = null;
    $data = false;
    $success = false;

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if ($kirby->request()->is('POST') && get('submit')) {

        // check the honeypot
        if (trim((string)get('website')) !== '') {
            go($page->url());
        }

        $postedToken = (string)get('contact_form_token');
        $sessionToken = $_SESSION['contact_form']['token'] ?? '';
        $sessionTimestamp = (int)($_SESSION['contact_form']['timestamp'] ?? 0);
        unset($_SESSION['contact_form']);

        $now = time();
        $minimumSeconds = 5;
        $staleSeconds = 3600;
        $isTokenValid = $postedToken !== '' && $sessionToken !== '' && hash_equals($sessionToken, $postedToken);
        $isTooFast = $isTokenValid && ($sessionTimestamp <= 0 || $sessionTimestamp > $now || $now - $sessionTimestamp < $minimumSeconds);
        $isStale = $isTokenValid && $now - $sessionTimestamp > $staleSeconds;

        $data = [
            'name'  => get('name'),
            'email' => get('email'),
            'text'  => get('text')
        ];

        $rules = [
            'name'  => ['required', 'minLength' => 3],
            'email' => ['required', 'email'],
            'text'  => ['required', 'minLength' => 3, 'maxLength' => 3000],
        ];

        $messages = [
            'name'  => 'Bitte geben Sie einen gültigen Namen ein.',
            'email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
            'text'  => 'Bitte geben Sie einen Text mit maximal 3000 Zeichen ein.'
        ];

        if ($invalid = invalid($data, $rules, $messages)) {
            $alert = $invalid;
        } else {
            if (!$isTokenValid || $isTooFast) {
                go($page->url());
            } elseif ($isStale) {
                $alert['error'] = 'Bitte aktualisieren Sie das Formular und senden Sie es erneut.';
            } else {
                try {
                    $kirby->email([
                        'template' => 'contact-form-notification',
                        'from'     => Helper::getEnv('KIRBY_MAIL_FROM'),
                        'replyTo'  => $data['email'],
                        'to'       => $page->content()->get('contactFormRecipientEmail')->or('info@cvjm-kreisverband.de')->value(),
                        'subject'  => 'Eine neue Nachricht über das Kontaktformular von "' . Str::short(esc($data['name']), 12, '...') . '"',
                        'data'     => [
                            'text'       => esc($data['text']),
                            'sender'     => esc($data['name']),
                            'senderMail' => esc($data['email']),
                            'siteName'   => esc($kirby->site()->title()),
                        ]
                    ]);
                } catch (Exception $error) {
                    if (option('debug')) {
                        $alert['error'] = 'Das Formular konnte nicht gesendet werden: <strong>' . $error->getMessage() . '</strong>';
                    } else {
                        $alert['error'] = 'Das Formular konnte nicht gesendet werden!';
                    }
                }

                if (empty($alert) === true) {
                    $success = 'Ihre Nachricht wurde gesendet, vielen Dank. Wir werden uns bald bei Ihnen melden!';
                    $data = [];
                }
            }
        }
    }

    $contactFormToken = bin2hex(random_bytes(16));
    $_SESSION['contact_form'] = [
        'token'     => $contactFormToken,
        'timestamp' => time(),
    ];

    return [
        'alert'            => $alert,
        'data'             => $data,
        'success'          => $success,
        'contactFormToken' => $contactFormToken,
    ];
};
