<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\MailChimpPlugin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use DrewM\MailChimp\MailChimp;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Webmozart\Assert\Assert;

class NewsletterSubscriptionHandler
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var FactoryInterface
     */
    private $customerFactory;

    /**
     * @var EntityManagerInterface
     */
    private $customerManager;

    /**
     * @var string $listId
     */
    private $listId;

    /**
     * @var MailChimp
     */
    private $mailChimp;

    /**
     * NewsletterSubscriptionHandler constructor.
     * @param CustomerRepositoryInterface $customerRepository
     * @param FactoryInterface $customerFactory
     * @param EntityManagerInterface $customerManager
     * @param MailChimp $mailChimp
     * @param string $listId
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        FactoryInterface $customerFactory,
        EntityManagerInterface $customerManager,
        MailChimp $mailChimp,
        string $listId
    )
    {
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->customerManager = $customerManager;
        $this->mailChimp = $mailChimp;
        $this->listId = $listId;
    }

    /**
     * @param string|null $email
     */
    public function subscribe(?string $email): void
    {
        $customer = $this->customerRepository->findOneBy(['email' => $email]);

        if ($customer instanceof CustomerInterface) {
            $this->updateCustomer($customer);
        } else {
            $this->createNewCustomer($email);
        }

        $response = $this->mailChimp->get('lists/' . $this->listId . '/members/' . $this->getEmailHash($email));
        Assert::keyExists($response, 'status');

        if ($response['status'] === Response::HTTP_NOT_FOUND) {
            $this->exportNewEmail($email);
        }
    }

    /**
     * @param CustomerInterface $customer
     */
    public function unsubscribe(CustomerInterface $customer): void
    {
        $this->updateCustomer($customer, false);
        $email = $customer->getEmail();
        $this->mailChimp->delete('lists/' . $this->listId . '/members/' . $this->getEmailHash($email));
    }

    /**
     * @param string $email
     */
    private function createNewCustomer(string $email): void
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerFactory->createNew();

        $customer->setEmail($email);
        $customer->setSubscribedToNewsletter(true);

        $this->customerRepository->add($customer);
    }

    /**
     * @param string $email
     */
    private function exportNewEmail(string $email): void
    {
        $response = $this->mailChimp->post("lists/" . $this->listId . "/members", [
            'email_address' => $email,
            'status' => 'subscribed',
        ]);

        Assert::keyExists($response, 'status');

        if ($response['status'] !== 'subscribed') {
            throw new BadRequestHttpException();
        }
    }

    /**
     * @param CustomerInterface $customer
     * @param bool $subscribedToNewsletter
     */
    private function updateCustomer(CustomerInterface $customer, $subscribedToNewsletter = true): void
    {
        $customer->setSubscribedToNewsletter($subscribedToNewsletter);
        $this->customerManager->flush();
    }

    /**
     * @param string $email
     *
     * @return string
     */
    private function getEmailHash(string $email): string
    {
        return md5(strtolower($email));
    }
}