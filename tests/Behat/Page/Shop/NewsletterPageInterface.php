<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace Tests\BitBag\MailChimpPlugin\Behat\Page\Shop;

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