<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
