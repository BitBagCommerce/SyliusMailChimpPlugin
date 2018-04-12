<?php

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Validator\Constraints;

use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class UniqueNewsletterEmailValidator extends ConstraintValidator
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * {@inheritdoc]
     */
    public function validate($email, Constraint $constraint)
    {
        if ($this->isEmailValid($email) === false) {
            $this->context->addViolation($constraint->message);
        }
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    private function isEmailValid($email)
    {
        $customer = $this->customerRepository->findOneBy(['email' => $email]);

        if ($customer instanceof CustomerInterface === false) {
            return true;
        }

        if ($customer->isSubscribedToNewsletter() === true) {
            return false;
        }

        return true;
    }
}
