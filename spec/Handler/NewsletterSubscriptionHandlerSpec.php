<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusMailChimpPlugin\Handler;

use BitBag\SyliusMailChimpPlugin\Handler\NewsletterSubscriptionHandler;
use Doctrine\ORM\EntityManagerInterface;
use DrewM\MailChimp\MailChimp;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class NewsletterSubscriptionHandlerSpec extends ObjectBehavior
{
    const EMAIL = 'some@email.com';

    const API_KEY = 'TEST123';

    const LIST_ID = '1234';

    function let(
        CustomerRepositoryInterface $customerRepository,
        FactoryInterface $customerFactory,
        EntityManagerInterface $customerManager,
        MailChimp $mailChimp
    ) {
        $this->beConstructedWith(
            $customerRepository,
            $customerFactory,
            $customerManager,
            $mailChimp,
            self::LIST_ID
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NewsletterSubscriptionHandler::class);
    }

    function it_subscribes_new_customer(
        CustomerRepositoryInterface $customerRepository,
        FactoryInterface $customerFactory,
        CustomerInterface $customer,
        MailChimp $mailChimp
    ) {
        $customerRepository->findOneBy(['email' => self::EMAIL])->willReturn(null);
        $customerFactory->createNew()->willReturn($customer);
        $customerRepository->add($customer)->shouldBeCalled();
        $mailChimp->get('lists/1234/members/d8ffeba65ee5baf57e4901690edc8e1b')->willReturn(['status' => 200]);

        $this->subscribe(self::EMAIL);
    }

    function it_subscribes_existing_customer(
        CustomerRepositoryInterface $customerRepository,
        CustomerInterface $customer,
        MailChimp $mailChimp
    ) {
        $customerRepository->findOneBy(['email' => self::EMAIL])->willReturn($customer);
        $customer->isSubscribedToNewsletter()->willReturn(false);
        $customer->setSubscribedToNewsletter(true)->shouldBeCalled();
        $mailChimp->get('lists/1234/members/d8ffeba65ee5baf57e4901690edc8e1b')->willReturn(['status' => 200]);

        $this->subscribe(self::EMAIL);
    }

    function it_unsubscribes_existing_customer(CustomerInterface $customer)
    {
        $customer->setSubscribedToNewsletter(false)->shouldBeCalled();
        $customer->getEmail()->willReturn(self::EMAIL);

        $this->unsubscribe($customer);
    }
}
