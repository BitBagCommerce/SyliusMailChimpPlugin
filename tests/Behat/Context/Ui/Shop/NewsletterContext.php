<?php

namespace Tests\BitBag\SyliusMailChimpPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Behat\Service\SharedStorage;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Tests\BitBag\SyliusMailChimpPlugin\Behat\Page\Shop\NewsletterPageInterface;
use Tests\BitBag\SyliusMailChimpPlugin\Behat\Page\Shop\ProfileUpdatePageInterface;
use Webmozart\Assert\Assert;

final class NewsletterContext implements Context
{
    /**
     * @var NewsletterPageInterface
     */
    private $newsletterPage;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var FactoryInterface
     */
    private $customerFactory;

    /**
     * @var CustomerInterface
     */
    private $customer;

    /**
     * @var EntityManagerInterface
     */
    private $customerManager;

    /**
     * @var SharedStorage
     */
    private $sharedStorage;

    /**
     * @var ProfileUpdatePageInterface
     */
    private $profileUpdatePage;

    /**
     * @param NewsletterPageInterface $newsletterPage
     * @param CustomerRepositoryInterface $customerRepository
     * @param FactoryInterface $customerFactory
     * @param EntityManagerInterface $customerManager
     * @param SharedStorage $sharedStorage
     * @param ProfileUpdatePageInterface $profileUpdatePage
     */
    public function __construct(
        NewsletterPageInterface $newsletterPage,
        CustomerRepositoryInterface $customerRepository,
        FactoryInterface $customerFactory,
        EntityManagerInterface $customerManager,
        SharedStorage $sharedStorage,
        ProfileUpdatePageInterface $profileUpdatePage
    )
    {
        $this->newsletterPage = $newsletterPage;
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->customerManager = $customerManager;
        $this->sharedStorage = $sharedStorage;
        $this->profileUpdatePage = $profileUpdatePage;
    }

    /**
     * @When I want to subscribe to the newsletter
     */
    public function iWantToSubscribeToTheNewsletter()
    {
        $this->newsletterPage->open();
    }

    /**
     * @When I fill newsletter with :email email
     */
    public function iFillNewsletterWithEmail($email)
    {
        $this->newsletterPage->fillEmail($email);
    }

    /**
     * @When I subscribe to it
     */
    public function iSubscribeToIt()
    {
        $this->newsletterPage->subscribe();
    }

    /**
     * @Then I should be notified that I am subscribed to the newsletter
     */
    public function iShouldBeNotifiedThatIAmSubscribedToTheNewsletter()
    {
        Assert::contains($this->newsletterPage->getContents(), "You are now subscribed to the newsletter");
    }

    /**
     * @Then the :email customer should be created
     */
    public function theCustomerShouldBeCreated($email)
    {
        $customer = $this->getCustomerByEmail($email);
        Assert::isInstanceOf($customer, CustomerInterface::class);

        $this->customer = $customer;
    }

    /**
     * @Then this customer should be subscribed to the newsletter
     */
    public function theCustomerShouldBeSubscribedToTheNewsletter()
    {
        Assert::true($this->customer->isSubscribedToNewsletter());
    }

    /**
     * @Given there is no customer with :email email
     */
    public function thereIsNoCustomerWithEmail($email)
    {
        $customer = $this->getCustomerByEmail($email);

        if ($customer instanceof ResourceInterface) {
            $this->customerRepository->remove($customer);
        }
    }

    /**
     * @Then I should be notified about invalid email address
     */
    public function iShouldBeNotifiedAboutInvalidEmailAddress()
    {
        Assert::contains($this->newsletterPage->getContents(), 'The submitted email address is not valid');
    }

    /**
     * @When the form token is set to :token
     */
    public function theFormTokenIsSetTo($token)
    {
        $this->newsletterPage->fillToken($token);
    }

    /**
     * @Then I should be notified that the submitted CSRF token is invalid
     */
    public function iShouldBeNotifiedThatTheSubmittedCsrfTokenIsInvalid()
    {
        Assert::contains($this->newsletterPage->getContents(), 'Submited CSRF token is invalid');
    }

    /**
     * @Then I should be notified that the submitted email is already subscribed to the newsletter
     */
    public function iShouldBeNotifiedThatTheSubmittedEmailIsAlreadySubscribedToTheNewsletter()
    {
        Assert::contains($this->newsletterPage->getContents(), "Given email address is already subscribed to the newsletter");
    }

    /**
     * @Given there is an existing customer with :email email
     */
    public function thereIsAnExistingCustomerWithEmail($email)
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerFactory->createNew();
        $customer->setEmail($email);
        $this->customerRepository->add($customer);

        $this->customer = $customer;
    }

    /**
     * @Given this customer is also subscribed to the newsletter
     */
    public function thisCustomerIsAlsoSubscribedToTheNewsletter()
    {
        $this->customer->setSubscribedToNewsletter(true);
        $this->customerManager->flush();
    }

    /**
     * @Given the :email customer is subscribed to the newsletter
     */
    public function theCustomerIsSubscribedToTheNewsletter($email)
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerRepository->findOneBy(['email' => $email]);
        Assert::isInstanceOf($customer, CustomerInterface::class);
        $customer->setSubscribedToNewsletter(true);
        $this->customerManager->flush();
        $this->sharedStorage->set('newsletter_email', $customer->getEmail());
    }

    /**
     * @param string $email
     * @return null|object|CustomerInterface
     */
    private function getCustomerByEmail($email)
    {
        return $this->customerRepository->findOneBy(['email' => $email]);
    }

    /**
     * @When I unsubscribe the newsletter
     */
    public function iUnsubscribeTheNewsletter()
    {
        $this->profileUpdatePage->unsubscribeNewsletter();
    }
}
