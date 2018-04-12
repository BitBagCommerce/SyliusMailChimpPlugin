<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusMailChimpPlugin\EventListener;

use BitBag\SyliusMailChimpPlugin\EventListener\CustomerNewsletterListener;
use BitBag\SyliusMailChimpPlugin\Handler\NewsletterSubscriptionHandler;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CustomerNewsletterListenerSpec extends ObjectBehavior
{
    function let(NewsletterSubscriptionHandler $newsletterSubscriptionHandler)
    {
        $this->beConstructedWith($newsletterSubscriptionHandler);
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
