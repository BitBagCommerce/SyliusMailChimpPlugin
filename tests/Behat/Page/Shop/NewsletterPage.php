<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusMailChimpPlugin\Behat\Page\Shop;

use Sylius\Behat\Page\Shop\HomePage;

class NewsletterPage extends HomePage implements NewsletterPageInterface
{
    /**
     * @inheritdoc
     */
    public function fillEmail($email)
    {
        $this->getDocument()->fillField('newsletter-email', $email);
    }

    /**
     * @param string $token
     */
    public function fillToken($token)
    {
        $this->getDocument()->find('css', '#newsletter-token')->setValue($token);
    }

    public function subscribe()
    {
        $this->getDocument()->pressButton('Subscribe');
    }
}
