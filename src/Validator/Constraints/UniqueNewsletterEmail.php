<?php

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class UniqueNewsletterEmail extends Constraint
{
    public $message = 'bitbag_sylius_mailchimp_plugin.ui.unique_email';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}
