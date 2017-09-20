<?php

namespace frontend\services\contact;

use frontend\forms\ContactForm;
use yii\mail\MailerInterface;

class ContactService
{
    private $mailer;
    private $adminEmail;

    public function __construct($adminEmail, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
    }

    public function send(ContactForm $form): void
    {
        $sent = $this->mailer->compose()
            ->setTo($this->adminEmail)
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }
    }
}