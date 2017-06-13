<?php

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
     * @return array
     */
    public function validate($email)
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