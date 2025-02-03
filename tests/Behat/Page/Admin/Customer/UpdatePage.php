<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusMailChimpPlugin\Behat\Page\Admin\Customer;

use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;

class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    public function selectOption(string $field, string $value): void
    {
        $this->getDocument()->selectFieldOption($field, $value);
    }

    public function submit(): void
    {
        $this->getDocument()->pressButton('Update');
    }
}
