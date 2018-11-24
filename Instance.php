<?php

namespace Flynt\Features\Mailchimp;

use DrewM\MailChimp\MailChimp;
use Flynt\Features\Acf\OptionPages;
use Vivalidator\Validator;

class Instance {
    public $Mailchimp = false;
    private $apiKey = false;

    public function __construct () {
        $apiKey = OptionPages::get('globalOptions', 'feature', 'mailchimp', 'apiKey');
        $this->apiKey = ($apiKey) ? $apiKey : false;

        if ($this->apiKey) {
            $this->Mailchimp = new MailChimp($this->apiKey);
        }
    }

    public function SubscribeToList ($id, $email, $status = 'pending') {
        $data = [
            'email' => $email,
            'list' => $id,
        ];

        $validator = new Validator($data, [
            'email' => [
                [
                    'rule' => 'required',
                    'error' => 'E-Mail-Adresse muss angegeben sein'
                ],
                [
                    'rule' => 'email',
                    'error' => 'E-Mail-Adresse muss valide sein'
                ]
            ],
            'list' => [
                [
                    'rule' => 'required',
                    'error' => 'Es gab ein Problem beim Eintragen in den Newsletter'
                ]
            ]
        ]);

        if (count($validator->errors)) {
            return [
                'status' => 500,
                'messages' => $validator->errors
            ];
        }

        return $this->Mailchimp->post(sprintf('lists/%s/members', $id), [
            'email_address' => $email,
            'status' => $status
        ]);
    }
}
