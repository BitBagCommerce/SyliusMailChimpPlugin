<?php

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\EventListener;

use BitBag\SyliusMailChimpPlugin\Handler\NewsletterSubscriptionHandler;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CustomerNewsletterListener
{
    /** @var NewsletterSubscriptionHandler */
    private $newsletterSubscriptionHandler;

    public function __construct(NewsletterSubscriptionHandler $newsletterSubscriptionHandler)
    {
        $this->newsletterSubscriptionHandler = $newsletterSubscriptionHandler;
    }

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

    public function customerUpdateEvent(GenericEvent $event): void
    {
        $this->customerCreateEvent($event);
    }

    private function subscribe(CustomerInterface $customer): void
    {
        $this->newsletterSubscriptionHandler->subscribe($customer);
    }

    private function unsubscribe(CustomerInterface $customer): void
    {
        $this->newsletterSubscriptionHandler->unsubscribe($customer);
    }
}
