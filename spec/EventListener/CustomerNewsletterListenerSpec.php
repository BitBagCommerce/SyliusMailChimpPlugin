<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMailChimpPlugin\EventListener;

use BitBag\SyliusMailChimpPlugin\EventListener\CustomerNewsletterListener;
use BitBag\SyliusMailChimpPlugin\Handler\NewsletterSubscriptionHandler;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CustomerNewsletterListenerSpec extends ObjectBehavior
{
    function let(NewsletterSubscriptionHandler $newsletterSubscriptionHandler, EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($newsletterSubscriptionHandler, $entityManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CustomerNewsletterListener::class);
    }

    function it_throws_exception_when_subject_is_not_customer_interface(
        GenericEvent $genericEvent
    ) {
        $genericEvent->getSubject()->willReturn(null);

        $this
            ->shouldThrow(new UnexpectedTypeException(null, CustomerInterface::class))
            ->during('customerCreateEvent', [$genericEvent])
        ;
    }

    function it_should_subscribe_customer_when_hes_not_subscribed(
        GenericEvent $genericEvent,
        CustomerInterface $customer
    ) {
        $genericEvent->getSubject()->willReturn($customer);
        $customer->isSubscribedToNewsletter()->willReturn(true);
        $customer->getEmail()->shouldBeCalled();

        $this->customerCreateEvent($genericEvent);
    }

    function it_should_usubscribe_customer(
        GenericEvent $genericEvent,
        CustomerInterface $customer
    ) {
        $genericEvent->getSubject()->willReturn($customer);
        $customer->isSubscribedToNewsletter()->willReturn(false);
        $customer->getEmail()->shouldNotBeCalled();

        $this->customerCreateEvent($genericEvent);
    }
}
