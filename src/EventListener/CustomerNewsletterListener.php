<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on tomasz.grochowski@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\EventListener;

use BitBag\SyliusMailChimpPlugin\Handler\NewsletterSubscriptionHandler;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CustomerNewsletterListener
{
    /**
     * @var NewsletterSubscriptionHandler
     */
    private $newsletterSubscriptionHandler;

    /**
     * @param NewsletterSubscriptionHandler $newsletterSubscriptionHandler
     */
    public function __construct(NewsletterSubscriptionHandler $newsletterSubscriptionHandler)
    {
        $this->newsletterSubscriptionHandler = $newsletterSubscriptionHandler;
    }

    /**
     * @param GenericEvent $event
     */
    public function customerCreateEvent(GenericEvent $event)
    {
        /** @var CustomerInterface $customer */
        $customer = $event->getSubject();

        if (!$customer instanceof CustomerInterface) {
            throw new UnexpectedTypeException(
                $customer,
                CustomerInterface::class
            );
        }

        $customer->isSubscribedToNewsletter() === false ? $this->unsubscribe($customer) : $this->subscribe($customer);
    }

    /**
     * @param GenericEvent $event
     */
    public function customerUpdateEvent(GenericEvent $event)
    {
        $this->customerCreateEvent($event);
    }

    /**
     * @param GenericEvent $event
     */
    public function customerPreUpdateEvent(GenericEvent $event)
    {
        /** @var CustomerInterface $customer */
        $customer = $event->getSubject();
        if (!$customer instanceof CustomerInterface) {
            throw new UnexpectedTypeException(
                $customer,
                CustomerInterface::class
            );
        }
    }

    private function subscribe(CustomerInterface $customer)
    {
        $this->newsletterSubscriptionHandler->subscribe($customer->getEmail());
    }

    private function unsubscribe(CustomerInterface $customer)
    {
        $this->newsletterSubscriptionHandler->unsubscribe($customer);
    }
}
