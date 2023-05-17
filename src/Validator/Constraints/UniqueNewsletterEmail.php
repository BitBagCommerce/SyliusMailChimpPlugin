<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class UniqueNewsletterEmail extends Constraint
{
    /** @var string */
    public $message = 'bitbag_sylius_mailchimp_plugin.ui.unique_email';

    public function validatedBy(): string
    {
        return static::class . 'Validator';
    }
}
