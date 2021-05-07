<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on tomasz.grochowski@bitbag.pl.
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

    /** @var string */
    private $listId;

    /**
     * @param ValidatorInterface $validator
     * @param string $listId
     */
    public function __construct(ValidatorInterface $validator, string $listId)
    {
        $this->validator = $validator;
    }

    /**
     * @param null|string $email
     * @return array
     */
    public function validate(?string $email): array
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

    /**
     * @param string|null $listId
     * @return array
     */
    public function validateListId(?string $listId): array
    {
        $errors = [];
        if($listId !== $this->listId){
            $errors[] = 'bitbag_sylius_mailchimp_plugin.ui.invalid_list_id';
        }
        return $errors;
    }
}
