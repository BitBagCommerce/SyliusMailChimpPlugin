<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
