<?php

namespace Tests\BitBag\MailChimpPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use DrewM\MailChimp\MailChimp;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

class MailChimpContext implements Context
{
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
    private $subscriberHash;

    public function __construct($apiKey, $listId)
    {
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
     * @Then the email :email should be exported to MailChimp's default list
     */
    public function theEmailShouldBeExportedToMailChimp($email)
    {
        $members = $this->getListMembers($this->listId);

        foreach ($members as $member) {
            Assert::keyExists($member, 'email_address');
            Assert::keyExists($member, 'id');

            if ($member['email_address'] === $email) {
                $this->subscriberHash = $member['id'];

                return;
            }
        }

        throw new NotFoundHttpException(
            sprintf(
                "The email %s doesn't exist in MailChimp with list with %s ID",
                $email,
                $this->listId
            ));
    }

    /**
     * @Given there is an existing :email email in MailChimp's default list
     */
    public function thereIsAnExistingEmailInMailChimp($email)
    {
        $response = $this->mailChimp->post($this->mailChimp->post("lists/" . $this->listId . "/members", [
            'email_address' => $email,
            'status' => 'subscribed',
        ]));
        Assert::keyExists($response, 'status');
        Assert::eq($response['status'], 200);
    }

    /**
     * @AfterScenario
     */
    public function removeClient()
    {
        $this->mailChimp->delete('lists/' . $this->listId . '/members/' . $this->subscriberHash);
    }

    private function getListMembers($listId)
    {
        $response = $this->mailChimp->get('lists/' . $listId . '/members');
        Assert::keyExists($response, 'members');

        return $response['members'];
    }
}
