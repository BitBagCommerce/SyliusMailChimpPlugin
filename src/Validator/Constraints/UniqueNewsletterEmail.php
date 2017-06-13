<?php

namespace BitBag\MailChimpPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueNewsletterEmail extends Constraint
{
    public $message = 'bitbag.mailchimp_plugin.unique_email';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}