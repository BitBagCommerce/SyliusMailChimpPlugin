<?php

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Validator;

use BitBag\SyliusMailChimpPlugin\Validator\Constraints\UniqueNewsletterEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class NewsletterValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $email
     *
     * @return array
     */
    public function validate($email)
    {
        $violations = $this->validator->validate($email, [
            new Email(['message' => 'bitbag_sylius_mailchimp_plugin.ui.invalid_email']),
            new NotBlank(['message' => 'bitbag_sylius_mailchimp_plugin.ui.email_not_blank']),
            new UniqueNewsletterEmail(),
        ]);

        $errors = [];

        if (count($violations) === 0) {
            return $errors;
        }

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }

        return $errors;
    }
}
