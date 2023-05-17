<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusMailChimpPlugin\Behat\Page\Shop;

use Sylius\Behat\Page\Shop\HomePageInterface;

interface NewsletterPageInterface extends HomePageInterface
{
    /**
     * @param string $email
     */
    public function fillEmail($email);

    /**
     * @param string $token
     */
    public function fillToken($token);

    public function subscribe();
}
