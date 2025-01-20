<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Sylius\Bundle\OrderBundle\SyliusOrderBundle::class => ['all' => true],
    Sylius\Bundle\MoneyBundle\SyliusMoneyBundle::class => ['all' => true],
    Sylius\Bundle\CurrencyBundle\SyliusCurrencyBundle::class => ['all' => true],
    Sylius\Bundle\LocaleBundle\SyliusLocaleBundle::class => ['all' => true],
    Sylius\Bundle\ProductBundle\SyliusProductBundle::class => ['all' => true],
    Sylius\Bundle\ChannelBundle\SyliusChannelBundle::class => ['all' => true],
    Sylius\Bundle\AttributeBundle\SyliusAttributeBundle::class => ['all' => true],
    Sylius\Bundle\TaxationBundle\SyliusTaxationBundle::class => ['all' => true],
    Sylius\Bundle\ShippingBundle\SyliusShippingBundle::class => ['all' => true],
    Sylius\Bundle\PaymentBundle\SyliusPaymentBundle::class => ['all' => true],
    Sylius\Bundle\MailerBundle\SyliusMailerBundle::class => ['all' => true],
    Sylius\Bundle\PromotionBundle\SyliusPromotionBundle::class => ['all' => true],
    Sylius\Bundle\AddressingBundle\SyliusAddressingBundle::class => ['all' => true],
    Sylius\Bundle\InventoryBundle\SyliusInventoryBundle::class => ['all' => true],
    Sylius\Bundle\TaxonomyBundle\SyliusTaxonomyBundle::class => ['all' => true],
    Sylius\Bundle\UserBundle\SyliusUserBundle::class => ['all' => true],
    Sylius\Bundle\CustomerBundle\SyliusCustomerBundle::class => ['all' => true],
    Sylius\Bundle\UiBundle\SyliusUiBundle::class => ['all' => true],
    Sylius\Bundle\ReviewBundle\SyliusReviewBundle::class => ['all' => true],
    Sylius\Bundle\CoreBundle\SyliusCoreBundle::class => ['all' => true],
    Sylius\Bundle\ResourceBundle\SyliusResourceBundle::class => ['all' => true],
    Sylius\Bundle\GridBundle\SyliusGridBundle::class => ['all' => true],
    Knp\Bundle\GaufretteBundle\KnpGaufretteBundle::class => ['all' => true],
    Knp\Bundle\MenuBundle\KnpMenuBundle::class => ['all' => true],
    Liip\ImagineBundle\LiipImagineBundle::class => ['all' => true],
    Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle::class => ['all' => true],
    BabDev\PagerfantaBundle\BabDevPagerfantaBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Sylius\Bundle\FixturesBundle\SyliusFixturesBundle::class => ['all' => true],
    Sylius\Bundle\ThemeBundle\SyliusThemeBundle::class => ['all' => true],
    Sylius\Bundle\AdminBundle\SyliusAdminBundle::class => ['all' => true],
    Sylius\Bundle\ShopBundle\SyliusShopBundle::class => ['all' => true],
    BitBag\SyliusMailChimpPlugin\BitBagSyliusMailChimpPlugin::class => ['all' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true, 'test' => true, 'test_cached' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true, 'test_cached' => true],
    Fidry\AliceDataFixtures\Bridge\Symfony\FidryAliceDataFixturesBundle::class => ['dev' => true, 'test' => true, 'test_cached' => true],
    Nelmio\Alice\Bridge\Symfony\NelmioAliceBundle::class => ['dev' => true, 'test' => true, 'test_cached' => true],
    FriendsOfBehat\SymfonyExtension\Bundle\FriendsOfBehatSymfonyExtensionBundle::class => ['test' => true, 'test_cached' => true],
    Sylius\Behat\Application\SyliusTestPlugin\SyliusTestPlugin::class => ['test' => true, 'test_cached' => true],
    ApiPlatform\Symfony\Bundle\ApiPlatformBundle::class => ['all' => true],
    Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle::class => ['all' => true],
    Sylius\Bundle\ApiBundle\SyliusApiBundle::class => ['all' => true],
    SyliusLabs\DoctrineMigrationsExtraBundle\SyliusLabsDoctrineMigrationsExtraBundle::class => ['all' => true],
    League\FlysystemBundle\FlysystemBundle::class => ['all' => true],
    Symfony\WebpackEncoreBundle\WebpackEncoreBundle::class => ['all' => true],
    Sylius\TwigHooks\SyliusTwigHooksBundle::class => ['all' => true],
    Symfony\UX\TwigComponent\TwigComponentBundle::class => ['all' => true],
    Symfony\UX\LiveComponent\LiveComponentBundle::class => ['all' => true],
    Symfony\UX\Autocomplete\AutocompleteBundle::class => ['all' => true],
    Symfony\UX\StimulusBundle\StimulusBundle::class => ['all' => true],
    Sylius\TwigExtra\Symfony\SyliusTwigExtraBundle::class => ['all' => true],
    Symfony\UX\Icons\UXIconsBundle::class => ['all' => true],
    Sylius\Abstraction\StateMachine\SyliusStateMachineAbstractionBundle::class => ['all' => true],
];