<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusMailChimpPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use DrewM\MailChimp\MailChimp;
use Sylius\Behat\Service\SharedStorageInterface;
use Tests\BitBag\SyliusMailChimpPlugin\Behat\Fake\AlwaysSuccessMailChimpClient;
use Webmozart\Assert\Assert;

final class MailChimpContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var MailChimp */
    private $mailChimp;

    /** @var string */
    private $listId;

    /** @var string */
    private $subscribedEmail;

    /**
     * MailChimpContext constructor.
     *
     * @param string $apiKey
     * @param string $listId
     */
    public function __construct(SharedStorageInterface $sharedStorage, $apiKey, $listId)
    {
        $this->sharedStorage = $sharedStorage;
        $this->mailChimp = new AlwaysSuccessMailChimpClient($apiKey);
        $this->listId = $listId;
    }

    /**
     * @Given this email is also subscribed to the default MailChimp list
     */
    public function thisEmailIsAlsoExportedToMailChimpDefaultList()
    {
        $email = $this->sharedStorage->get('newsletter_email');
        Assert::notNull($email);
        $this->thereIsAnExistingEmailInMailChimpDefaultList($email);
    }

    /**
     * @Then the email :email should not be in MailChimp's list
     */
    public function theEmailShouldNotBeInMailChimpList($email)
    {
        $emailHash = $this->getSubscriberHash($email);
        $response = $this->mailChimp->get('lists/' . $this->listId . '/members/' . $emailHash);
        Assert::keyExists($response, 'status');
        Assert::eq($response['status'], 'subscribed', sprintf(
            "The email %s doesn't exist in MailChimp with list with %s ID",
            $email,
            $this->listId
        ));
        $this->subscribedEmail = $email;
    }

    /**
     * @Then the email :email should be exported to MailChimp's default list
     */
    public function theEmailShouldBeExportedToMailChimp($email)
    {
        $emailHash = $this->getSubscriberHash($email);
        $response = $this->mailChimp->get('lists/' . $this->listId . '/members/' . $emailHash);
        Assert::keyExists($response, 'status');
        Assert::eq($response['status'], 'subscribed', sprintf(
            "The email %s doesn't exist in MailChimp with list with %s ID",
            $email,
            $this->listId
        ));
    }

    /**
     * @Given there is an existing :email email in MailChimp's default list
     */
    public function thereIsAnExistingEmailInMailChimpDefaultList($email)
    {
        $response = $this->mailChimp->post('lists/' . $this->listId . '/members', [
            'email_address' => $email,
            'status' => 'subscribed',
        ]);
        Assert::keyExists($response, 'status');
        Assert::eq($response['status'], 'subscribed');
    }

    /**
     * @AfterScenario :email
     */
    public function removeNewsletterEmail()
    {
        $subscriberHash = $this->getSubscriberHash($this->subscribedEmail);
        $this->mailChimp->delete('lists/' . $this->listId . '/members/' . $subscriberHash);
    }

    /**
     * @param string $email
     *
     * @return string
     */
    private function getSubscriberHash($email)
    {
        return md5(strtolower($email));
    }
}
