<?php

namespace Flynt\Features\Mailchimp;

use DrewM\MailChimp\MailChimp;
use Flynt\Features\Acf\OptionPages;
use Vivalidator\Validator;

class Instance
{
    public $Mailchimp = false;
    private $apiKey = false;
    private $translations = false;

    public function __construct ()
    {
        $apiKey = OptionPages::get('globalOptions', 'feature', 'mailchimp', 'apiKey');
        $this->apiKey = ($apiKey) ? $apiKey : false;
        $this->translations = $this->getTranslations();

        if ($this->apiKey)
        {
            $this->Mailchimp = new MailChimp($this->apiKey);
        }
    }

    private function getTranslations ()
    {
        return [
            'errorEmailRequired' => $apiKey = OptionPages::get('translatableOptions', 'feature', 'mailchimp', 'errorEmailRequired'),
            'errorEmailNotValid' => $apiKey = OptionPages::get('translatableOptions', 'feature', 'mailchimp', 'errorEmailNotValid'),
            'errorSubscribing' => $apiKey = OptionPages::get('translatableOptions', 'feature', 'mailchimp', 'errorSubscribing')
        ];
    }

    public function SubscribeToList ($id, $email, $status = 'pending')
    {
        $data = [
            'email' => $email,
            'list' => $id,
        ];

        $validator = new Validator($data, [
            'email' => [
                [
                    'rule' => 'required',
                    'error' => $this->translations['errorEmailRequired']
                ],
                [
                    'rule' => 'email',
                    'error' => $this->translations['errorEmailNotValid']
                ]
            ],
            'list' => [
                [
                    'rule' => 'required',
                    'error' => $this->translations['errorSubscribing']
                ]
            ]
        ]);

        if (count($validator->errors))
        {
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
