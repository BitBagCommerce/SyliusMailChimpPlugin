<?php

namespace Tests\BitBag\SyliusMailChimpPlugin\Behat\Page\Shop;

use Sylius\Behat\Page\Shop\HomePageInterface;

interface NewsletterPageInterface extends HomePageInterface
{
    /**
     * @param string
     */
    public function fillEmail($email);

    /**
     * @param string
     */
    public function fillToken($token);

    public function subscribe();
}