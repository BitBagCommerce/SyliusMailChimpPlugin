<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
