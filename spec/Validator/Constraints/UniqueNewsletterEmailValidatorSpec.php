<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusMailChimpPlugin\Validator\Constraints;

use BitBag\SyliusMailChimpPlugin\Validator\Constraints\UniqueNewsletterEmail;
use BitBag\SyliusMailChimpPlugin\Validator\Constraints\UniqueNewsletterEmailValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class UniqueNewsletterEmailValidatorSpec extends ObjectBehavior
{
    const EMAIL = 'some@email.com';

    function let(CustomerRepositoryInterface $customerRepository, ExecutionContextInterface $executionContext)
    {
        $this->beConstructedWith($customerRepository);
        $this->initialize($executionContext);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UniqueNewsletterEmailValidator::class);
    }

    function it_is_constraint_validator()
    {
        $this->shouldHaveType(ConstraintValidator::class);
    }

    function it_adds_violation_if_email_is_not_unique(
        ExecutionContextInterface $executionContext,
        CustomerRepositoryInterface $customerRepository,
        CustomerInterface $customer
    ) {
        $customerRepository->findOneBy(['email' => self::EMAIL])->willReturn($customer);
        $customer->isSubscribedToNewsletter()->willReturn(true);

        $executionContext->addViolation('Email is not unique')->shouldBeCalled();

        $uniqueNewsletterEmailConstraint = new UniqueNewsletterEmail();
        $uniqueNewsletterEmailConstraint->message = 'Email is not unique';

        $this->validate('some@email.com', $uniqueNewsletterEmailConstraint);
    }

    function it_does_not_adds_violation_if_is_not_subscribed_to_newsletter(
        ExecutionContextInterface $executionContext,
        CustomerRepositoryInterface $customerRepository,
        CustomerInterface $customer
    ) {
        $customerRepository->findOneBy(['email' => self::EMAIL])->willReturn($customer);
        $customer->isSubscribedToNewsletter()->willReturn(false);

        $executionContext->addViolation(Argument::any())->shouldNotBeCalled();

        $uniqueNewsletterEmailConstraint = new UniqueNewsletterEmail();

        $this->validate('some@email.com', $uniqueNewsletterEmailConstraint);
    }

    function it_does_not_adds_violation_if_customer_not_exist(
        ExecutionContextInterface $executionContext,
        CustomerRepositoryInterface $customerRepository
    ) {
        $customerRepository->findOneBy(['email' => self::EMAIL])->willReturn(null);

        $executionContext->addViolation(Argument::any())->shouldNotBeCalled();

        $uniqueNewsletterEmailConstraint = new UniqueNewsletterEmail();

        $this->validate('some@email.com', $uniqueNewsletterEmailConstraint);
    }
}
