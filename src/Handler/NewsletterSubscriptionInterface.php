<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Handler;

use Sylius\Component\Core\Model\CustomerInterface;

interface NewsletterSubscriptionInterface
{
    public function subscribe(string $email): void;

    public function unsubscribe(CustomerInterface $customer): void;

    public function unsubscribeEmail(string $email): void;

    public function unsubscribeCustomerFromLocalDatabase(string $email): void;
}
