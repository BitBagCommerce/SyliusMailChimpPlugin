<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusMailChimpPlugin\Behat\Page\Shop;

use Sylius\Behat\Page\Shop\Account\ProfileUpdatePage as BaseProfileUpdatePage;

class ProfileUpdatePage extends BaseProfileUpdatePage implements ProfileUpdatePageInterface
{
    public function unsubscribeNewsletter()
    {
        $this->getDocument()->uncheckField('Subscribe to the newsletter');
    }
}
