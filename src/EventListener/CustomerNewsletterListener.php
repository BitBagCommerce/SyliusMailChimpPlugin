<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\MailChimpPlugin\EventListener;

use BitBag\MailChimpPlugin\Handler\NewsletterSubscriptionHandler;
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
    public function customerCreateEvent(GenericEvent $event): void
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
    public function customerUpdateEvent(GenericEvent $event): void
    {
        $this->customerCreateEvent($event);
    }

    /**
     * @param CustomerInterface $customer
     */
    private function subscribe(CustomerInterface $customer): void
    {
        $this->newsletterSubscriptionHandler->subscribe($customer->getEmail());
    }

    /**
     * @param CustomerInterface $customer
     */
    private function unsubscribe(CustomerInterface $customer): void
    {
        $this->newsletterSubscriptionHandler->unsubscribe($customer);
    }
}