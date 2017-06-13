<?php

namespace spec\BitBag\MailChimpPlugin\Validator\Constraints;

use BitBag\MailChimpPlugin\Validator\Constraints\UniqueNewsletterEmail;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\Constraint;

final class UniqueNewsletterEmailSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UniqueNewsletterEmail::class);
    }

    function it_has_constraint_type()
    {
        $this->shouldHaveType(Constraint::class);
    }

    function it_points_proper_validator_class()
    {
        $this->validatedBy()->shouldReturn('BitBag\MailChimpPlugin\Validator\Constraints\UniqueNewsletterEmailValidator');
    }
}
