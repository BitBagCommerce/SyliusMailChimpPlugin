<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Validator;

use BitBag\SyliusMailChimpPlugin\Model\WebhookData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class WebhookValidator
{
    /** @var ValidatorInterface */
    private $validator;

    /** @var string */
    private $listId;

    /** @var string */
    private $webhookSecret;

    public function __construct(
        ValidatorInterface $validator,
        string $listId,
        string $webhookSecret
    ) {
        $this->validator = $validator;
        $this->listId = $listId;
        $this->webhookSecret = $webhookSecret;
    }

    public function validate(WebhookData $webhookData): array
    {
        $data = $webhookData->getData();
        $violations = $this->validator->validate($data, [
            new Collection([
                'allowExtraFields' => true,
                'allowMissingFields' => false,
                'fields' => [
                    'list_id' => [
                        new NotBlank(),
                        new Type(['type' => 'string']),
                    ],
                    'status' => [
                        new NotBlank(),
                        new Type(['type' => 'string']),
                    ],
                    'id' => [
                        new NotBlank(),
                        new Type(['type' => 'string']),
                    ],
                    'email' => [
                        new NotBlank(),
                        new Type(['type' => 'string']),
                    ],
                ],
            ]),
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

    public function isListIdValid(?string $listId): bool
    {
        return $listId === $this->listId;
    }

    public function isRequestValid(Request $request): bool
    {
        return $request->query->get('qsecret') === $this->webhookSecret;
    }
}
