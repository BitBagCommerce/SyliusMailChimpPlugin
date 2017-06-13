<?php

namespace spec\BitBag\MailChimpPlugin\Validator;

use BitBag\MailChimpPlugin\Validator\Constraints\UniqueNewsletterEmail;
use BitBag\MailChimpPlugin\Validator\NewsletterValidator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class NewsletterValidatorSpec extends ObjectBehavior
{
    const EMAIL = 'some@email.com';

    const INVALID_EMAIL = 'dummyemail.com';

    function let(ValidatorInterface $validator)
    {
        $this->beConstructedWith($validator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NewsletterValidator::class);
    }

    function it_returns_an_error_in_case_of_invalid_email(
        ValidatorInterface $validator,
        ConstraintViolationListInterface $constraintViolationList,
        ConstraintViolationInterface $constraintViolation
    )
    {
        $validator->validate(self::INVALID_EMAIL, [
                new Email(['message' => 'bitbag.mailchimp_plugin.invalid_email']),
                new NotBlank(['message' => 'bitbag.mailchimp_plugin.email_not_blank']),
                new UniqueNewsletterEmail(),
            ]
        )->willReturn($constraintViolationList);

        $constraintViolationList->rewind()->shouldBeCalled();
        $constraintViolationList->valid()->willReturn(true, false);
        $constraintViolationList->current()->willReturn($constraintViolation);
        $constraintViolationList->next()->shouldBeCalled();
        $constraintViolationList->count()->shouldBeCalled();

        $this->validate('dummyemail.com')->shouldHaveCount(1);
    }

    function it_does_not_return_error_in_case_of_invalid_email(
        ValidatorInterface $validator,
        ConstraintViolationListInterface $constraintViolationList
    )
    {
        $validator->validate(self::EMAIL, [
                new Email(['message' => 'bitbag.mailchimp_plugin.invalid_email']),
                new NotBlank(['message' => 'bitbag.mailchimp_plugin.email_not_blank']),
                new UniqueNewsletterEmail(),
            ]
        )->willReturn($constraintViolationList);

        $constraintViolationList->count()->willReturn(0);

        $this->validate('some@email.com')->shouldHaveCount(0);
    }
}
