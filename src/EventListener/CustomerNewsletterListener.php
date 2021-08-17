<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\EventListener;

use BitBag\SyliusMailChimpPlugin\Handler\NewsletterSubscriptionHandler;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CustomerNewsletterListener
{
    /** @var NewsletterSubscriptionHandler */
    private $newsletterSubscriptionHandler;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(NewsletterSubscriptionHandler $newsletterSubscriptionHandler, EntityManagerInterface $entityManager)
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
