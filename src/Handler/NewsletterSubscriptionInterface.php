<?php

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Handler;

use Sylius\Component\Core\Model\CustomerInterface;

interface NewsletterSubscriptionInterface
{
    public function subscribe(string $email): void;

    public function unsubscribe(CustomerInterface $customer): void;
    
    public function unsubscribeEmail(string $email): void;
}
