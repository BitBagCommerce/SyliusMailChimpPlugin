<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Validator\Constraints;

use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

final class UniqueNewsletterEmailValidator extends ConstraintValidator
{
    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param mixed $value
     * @param UniqueNewsletterEmail|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        Assert::isInstanceOf($constraint, UniqueNewsletterEmail::class);

        if (false === $this->isEmailValid($value)) {
            $this->context->addViolation($constraint->message);
        }
    }

    private function isEmailValid(?string $email): bool
    {
        $customer = $this->customerRepository->findOneBy(['email' => $email]);

        if (false === $customer instanceof CustomerInterface) {
            return true;
        }

        if (true === $customer->isSubscribedToNewsletter()) {
            return false;
        }

        return true;
    }
}
