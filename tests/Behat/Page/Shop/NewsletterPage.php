<?php

namespace Tests\BitBag\SyliusMailChimpPlugin\Behat\Page\Shop;

use Sylius\Behat\Page\Shop\HomePage;

class NewsletterPage extends HomePage implements NewsletterPageInterface
{
    /**
     * {@inheritdoc}
     */
    public function fillEmail($email)
    {
        $this->getDocument()->fillField('newsletter-email', $email);
    }

    /**
     * @param string
     */
    public function fillToken($token)
    {
        $this->getDocument()->find('css','#newsletter-token')->setValue($token);
    }

    public function subscribe()
    {
        $this->getDocument()->pressButton('Subscribe');
    }
}