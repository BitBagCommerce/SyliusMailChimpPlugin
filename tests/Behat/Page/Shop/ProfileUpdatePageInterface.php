<?php

namespace Tests\BitBag\SyliusMailChimpPlugin\Behat\Page\Shop;

use Sylius\Behat\Page\Shop\Account\ProfileUpdatePageInterface as BaseProfileUpdatePageInterface;

interface ProfileUpdatePageInterface extends BaseProfileUpdatePageInterface
{
    public function unsubscribeNewsletter();
}