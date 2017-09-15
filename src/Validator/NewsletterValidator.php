<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\MailChimpPlugin\Validator;

use BitBag\MailChimpPlugin\Validator\Constraints\UniqueNewsletterEmail;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Email;

final class NewsletterValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param string $email
     *
     * @return array
     */
    public function validate(string $email): array
    {
        $violations = $this->validator->validate($email, [
            new Email(['message' => 'bitbag.mailchimp_plugin.invalid_email']),
            new NotBlank(['message' => 'bitbag.mailchimp_plugin.email_not_blank']),
            new UniqueNewsletterEmail(),
        ]);

        $errors = [];

        if ($violations->count() === 0) {
            return $errors;
        }

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }

        return $errors;
    }
}