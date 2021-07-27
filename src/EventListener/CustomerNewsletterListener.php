<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\EventListener;

use BitBag\SyliusMailChimpPlugin\Handler\NewsletterSubscriptionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CustomerNewsletterListener
{
    /** @var NewsletterSubscriptionInterface */
    private $newsletterSubscriptionHandler;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(NewsletterSubscriptionInterface $newsletterSubscriptionHandler, EntityManagerInterface $entityManager)
    {
        $this->newsletterSubscriptionHandler = $newsletterSubscriptionHandler;
        $this->entityManager = $entityManager;
    }

    public function customerCreateEvent(GenericEvent $event): void
    {
        $customer = $event->getSubject();

        if (!$customer instanceof CustomerInterface) {
            throw new UnexpectedTypeException(
                $customer,
                CustomerInterface::class
            );
        }

        $customer->isSubscribedToNewsletter() === false ? $this->unsubscribe($customer) : $this->subscribe($customer);
    }

    public function customerPostUpdateEvent(GenericEvent $event): void
    {
        $this->customerCreateEvent($event);
    }

    public function customerPreUpdateEvent(GenericEvent $event): void
    {
        $customer = $event->getSubject();

        if (!$customer instanceof CustomerInterface) {
            throw new UnexpectedTypeException(
                $customer,
                CustomerInterface::class
            );
        }

        $unitOfWork = $this->entityManager->getUnitOfWork();
        $unitOfWork->computeChangeSets();
        $changelist = $unitOfWork->getEntityChangeSet($customer);

        $oldEmail = $this->getOldEmailFromChangeList($changelist);

        if (null !== $oldEmail) {
            $this->newsletterSubscriptionHandler->unsubscribeEmail($oldEmail);
        }
    }

    private function subscribe(CustomerInterface $customer): void
    {
        if (null !== $customer->getEmail()) {
            $this->newsletterSubscriptionHandler->subscribe($customer->getEmail());
        }
    }

    private function unsubscribe(CustomerInterface $customer): void
    {
        $this->newsletterSubscriptionHandler->unsubscribe($customer);
    }

    private function getOldEmailFromChangeList(array $changelist): ?string
    {
        if (!array_key_exists('email', $changelist)) {
            return null;
        }
        $emailChanges = $changelist['email'];
        if (!is_array($emailChanges)) {
            return null;
        }
        $oldEmail = reset($emailChanges);
        if (false === $oldEmail) {
            return null;
        }

        return $oldEmail;
    }
}
