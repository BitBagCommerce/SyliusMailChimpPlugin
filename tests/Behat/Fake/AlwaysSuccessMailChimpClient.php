<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusMailChimpPlugin\Behat\Fake;

use DrewM\MailChimp\MailChimp;

final class AlwaysSuccessMailChimpClient extends MailChimp
{
    public function post($method, $args = [], $timeout = self::TIMEOUT)
    {
        return ['status' => 'subscribed'];
    }

    public function get($method, $args = [], $timeout = self::TIMEOUT)
    {
        return ['status' => 'subscribed'];
    }
}
