<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Validator;

use BitBag\SyliusMailChimpPlugin\Validator\Constraints\UniqueNewsletterEmail;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class NewsletterValidator
{
    /** @var ValidatorInterface */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(?string $email): array
    {
        $violations = $this->validator->validate($email, [
            new Email(['message' => 'bitbag_sylius_mailchimp_plugin.ui.invalid_email']),
            new NotBlank(['message' => 'bitbag_sylius_mailchimp_plugin.ui.email_not_blank']),
            new UniqueNewsletterEmail(),
        ]);

        $errors = [];

        if (0 === count($violations)) {
            return $errors;
        }

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }

        return $errors;
    }
}
