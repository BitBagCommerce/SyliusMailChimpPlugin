<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMailChimpPlugin\Validator\Constraints;

use BitBag\SyliusMailChimpPlugin\Validator\Constraints\UniqueNewsletterEmail;
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
        $this->validatedBy()->shouldReturn('BitBag\SyliusMailChimpPlugin\Validator\Constraints\UniqueNewsletterEmailValidator');
    }
}
