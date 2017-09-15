<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\MailChimpPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueNewsletterEmail extends Constraint
{
    /**
     * @var string
     */
    public $message = 'bitbag.mailchimp_plugin.unique_email';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return get_class($this).'Validator';
    }
}