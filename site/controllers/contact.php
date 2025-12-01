<?php
return function ($kirby, $pages, $page) {

    $alert = null;

    if ($kirby->request()->is('POST') && get('submit')) {

        // check the honeypot
        if (empty(get('website')) === false) {
            go($page->url());
        }

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

        // some of the data is invalid
        if ($invalid = invalid($data, $rules, $messages)) {
            $alert = $invalid;

            // the data is fine, let's send the email
        } else {
            try {
                $kirby->email([
                    'template' => 'contact-form-notification',
                    'from'     => 'kontaktformular@cvjm-kreisverband.de',
                    'replyTo'  => $data['email'],
                    'to'       => 'info@cvjm-kreisverband.de',
                    'subject'  => esc($data['name']) . ' hat Ihnen eine Nachricht über Ihr Kontaktformular gesendet',
                    'data'     => [
                        'text'   => esc($data['text']),
                        'sender' => esc($data['name']),
                        'senderMail' => esc($data['email']),
                        'siteName' => esc($kirby->site()->title()),
                    ]
                ]);
            } catch (Exception $error) {
                if (option('debug')):
                    $alert['error'] = 'Das Formular konnte nicht gesendet werden: <strong>' . $error->getMessage() . '</strong>';
                else:
                    $alert['error'] = 'Das Formular konnte nicht gesendet werden!';
                endif;
            }

            // no exception occurred, let's send a success message
            if (empty($alert) === true) {
                $success = 'Ihre Nachricht wurde gesendet, vielen Dank. Wir werden uns bald bei Ihnen melden!';
                $data = [];
            }
        }
    }

    return [
        'alert'   => $alert,
        'data'    => $data ?? false,
        'success' => $success ?? false
    ];
};
