<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use DrewM\MailChimp\MailChimp;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Webmozart\Assert\Assert;

class NewsletterSubscriptionHandler implements NewsletterSubscriptionInterface
{
    public const API_PATH_LISTS = 'lists';

    public const API_PATH_MEMBERS = 'members';

    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    /** @var FactoryInterface */
    private $customerFactory;

    /** @var EntityManagerInterface */
    private $customerManager;

    /** @var string */
    private $listId;

    /** @var MailChimp */
    private $mailChimp;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        FactoryInterface $customerFactory,
        EntityManagerInterface $customerManager,
        MailChimp $mailChimp,
        string $listId,
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->customerManager = $customerManager;
        $this->mailChimp = $mailChimp;
        $this->listId = $listId;
    }

    public function subscribe(string $email): void
    {
        $customer = $this->customerRepository->findOneBy(['email' => $email]);

        if (!$customer instanceof CustomerInterface) {
            $customer = $this->createNewCustomer($email);
        }

        $this->addMailchimpData($email);

        $customer->setSubscribedToNewsletter(true);
        $this->customerManager->flush();
    }

    public function getValidMailchimpListIds(): array
    {
        $responseArray = $this->mailChimp->get(self::API_PATH_LISTS);
        $ids = [];

        if (false === $responseArray) {
            return $ids;
        }

        $lists = $responseArray['lists'];
        foreach ($lists as $list) {
            $ids[] = $list['id'];
        }

        return $ids;
    }

    public function unsubscribe(CustomerInterface $customer): void
    {
        $this->updateCustomer($customer, false);
        $email = $customer->getEmail();
        if (null !== $email) {
            $this->unsubscribeEmail($email);
        }
    }

    public function unsubscribeEmail(string $email): void
    {
        $this->mailChimp->delete($this->getListMemberEndpoint($email));
    }

    public function unsubscribeCustomerFromLocalDatabase(string $email): void
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerRepository->findOneBy(['email' => $email]);
        $this->updateCustomer($customer, false);
    }

    private function createNewCustomer(string $email): CustomerInterface
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerFactory->createNew();
        $customer->setEmail($email);
        $this->customerRepository->add($customer);

        return $customer;
    }

    private function exportNewEmail(string $email): void
    {
        $response = $this->mailChimp->post($this->getListMemberEndpoint(), [
            'email_address' => $email,
            'status' => 'subscribed',
        ]);

        if (false === $response) {
            throw new BadRequestHttpException(
                sprintf(
                    'Mailchimp returned false instead of response array, last error : %s',
                    $this->mailChimp->getLastError(),
                ),
            );
        }

        Assert::keyExists($response, 'status');

        if (Response::HTTP_NOT_FOUND === $response['status']) {
            $validListIds = $this->getValidMailchimpListIds();
            $concatenatedList = implode(',', $validListIds);

            throw new BadRequestHttpException(
                sprintf(
                    'Mailchimp returned %1$d code, is the MAIL_CHIMP_LIST_ID [ %2$s ] one of available ones: [ %3$s ] ?',
                    Response::HTTP_NOT_FOUND,
                    $this->listId,
                    $concatenatedList,
                ),
            );
        }
        if ('subscribed' !== $response['status']) {
            throw new BadRequestHttpException(
                sprintf('Response status is %s instead of %s', $response['status'], 'subscribed'),
            );
        }
    }

    private function updateCustomer(CustomerInterface $customer, bool $subscribedToNewsletter = true): void
    {
        $customer->setSubscribedToNewsletter($subscribedToNewsletter);
        $this->customerManager->flush();
    }

    private function getEmailHash(string $email): string
    {
        return md5(strtolower($email));
    }

    protected function addMailchimpData(string $email): void
    {
        $response = $this->mailChimp->get($this->getListMemberEndpoint($email));

        if (false === $response) {
            throw new BadRequestHttpException(
                sprintf(
                    'Mailchimp returned false instead of response array, last error : %s',
                    $this->mailChimp->getLastError(),
                ),
            );
        }

        Assert::keyExists($response, 'status');

        if (Response::HTTP_UNAUTHORIZED === $response['status']) {
            Assert::keyExists($response, 'detail');

            throw new UnauthorizedHttpException('Mailchimp', $response['detail']);
        }

        if (Response::HTTP_NOT_FOUND === $response['status']) {
            $this->exportNewEmail($email);
        }
    }

    private function getListMemberEndpoint(string $email = null): string
    {
        $parts = [
            self::API_PATH_LISTS,
            $this->listId,
            self::API_PATH_MEMBERS,
        ];
        if (null !== $email) {
            $parts[] = $this->getEmailHash($email);
        }

        return implode('/', $parts);
    }
}
