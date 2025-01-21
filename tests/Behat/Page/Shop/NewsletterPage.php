<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
