<?php

namespace BitBag\MailChimpPlugin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use DrewM\MailChimp\MailChimp;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class NewsletterSubscriptionHandler
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
     * @var string $apiKey
     */
    private $apiKey;

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
     * @param string $apiKey
     * @param string $listId
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        FactoryInterface $customerFactory,
        EntityManagerInterface $customerManager,
        $apiKey,
        $listId
    )
    {
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->customerManager = $customerManager;
        $this->apiKey = $apiKey;
        $this->listId = $listId;
        $this->mailChimp = new MailChimp($this->apiKey);
    }


    /**
     * @param string $email
     */
    public function subscribe($email)
    {
        $customer = $this->customerRepository->findOneBy(['email' => $email]);

        if ($customer instanceof CustomerInterface) {
            $this->updateCustomer($customer);
        } else {
            $this->createNewCustomer($email);
        }

        $this->exportNewEmail($email);
    }

    /**
     * @param CustomerInterface $customer
     */
    private function updateCustomer(CustomerInterface $customer)
    {
        $customer->setSubscribedToNewsletter(true);
        $this->customerManager->flush();
    }

    /**
     * @param string $email
     */
    private function createNewCustomer($email)
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
    private function exportNewEmail($email)
    {
        $this->mailChimp->post("lists/" . $this->listId . "/members", [
            'email_address' => $email,
            'status' => 'subscribed',
        ]);
    }
}