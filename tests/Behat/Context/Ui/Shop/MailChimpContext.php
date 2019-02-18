<?php

namespace Tests\BitBag\SyliusMailChimpPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use DrewM\MailChimp\MailChimp;
use Sylius\Behat\Service\SharedStorageInterface;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class MailChimpContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var MailChimp
     */
    private $mailChimp;

    /**
     * @var string
     */
    private $listId;

    /**
     * @var string
     */
    private $subscribedEmail;

    /**
     * MailChimpContext constructor.
     * @param SharedStorageInterface $sharedStorage
     * @param string $apiKey
     * @param string $listId
     */
    public function __construct(SharedStorageInterface $sharedStorage, $apiKey, $listId)
    {
        $this->sharedStorage = $sharedStorage;
        $this->mailChimp = new MailChimp($apiKey);
        $this->listId = $listId;
    }

    /**
     * @Given there is a created list in MailChimp with specified ID
     */
    public function thereIsAMailChimpListWithSpecifiedId()
    {
        Assert::notNull($this->listId);
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
        $this->subscribedEmail = $email;
    }

    /**
     * @Given there is an existing :email email in MailChimp's default list
     */
    public function thereIsAnExistingEmailInMailChimpDefaultList($email)
    {
        $response = $this->mailChimp->post("lists/" . $this->listId . "/members", [
            'email_address' => $email,
            'status' => 'subscribed',
        ]);
        Assert::keyExists($response, 'status');
        Assert::eq($response['status'], 'subscribed');
    }

    /**
     * @Then the email :email should be removed from MailChimp's default list
     */
    public function theEmailShouldBeRemovedFromMailchimpSDefaultList($email)
    {
        $emailHash = $this->getSubscriberHash($email);
        $response = $this->mailChimp->get('lists/' . $this->listId . '/members/' . $emailHash);
        Assert::keyExists($response, 'status');
        Assert::eq($response['status'], Response::HTTP_NOT_FOUND);
        $this->subscribedEmail = $email;
    }

    /**
     * @AfterScenario
     */
    public function removeNewsletterEmail()
    {
        $subscriberHash = $this->getSubscriberHash($this->subscribedEmail);
        $this->mailChimp->delete('lists/' . $this->listId . '/members/' . $subscriberHash);
    }

    /**
     * @param string $email
     * @return string
     */
    private function getSubscriberHash($email)
    {
        return md5(strtolower($email));
    }
}
