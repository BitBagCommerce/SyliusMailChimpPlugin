<h1 align="center">
    <a href="http://bitbag.shop" target="_blank">
        <img src="doc/logo.png" width="55%" />
    </a>
    <br />
    <a href="https://packagist.org/packages/bitbag/mailchimp-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/bitbag/mailchimp-plugin.svg" />
    </a>
    <a href="http://travis-ci.org/BitBagCommerce/SyliusMailChimpPlugin" title="Build status" target="_blank">
        <img src="https://travis-ci.org/BitBagCommerce/SyliusMailChimpPlugin.svg?branch=master" />
    </a>
    <a href="https://packagist.org/packages/bitbag/mailchimp-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/bitbag/mailchimp-plugin.svg" />
    </a>
    <a href="https://scrutinizer-ci.com/g/BitBagCommerce/Sylius/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/BitBagCommerce/SyliusMailChimpPlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/bitbag/mailchimp-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/bitbag/mailchimp-plugin/downloads" />
    </a>
    <p>
        <img src="https://sylius.com/assets/badge-approved-by-sylius.png" width="85">
    </p>
</h1>

## BitBag SyliusMailChimpPlugin

This plugin allows you to integrate MailChimp newsletter sign-in process with Sylius
platform through customer registration, account updates or footer/modal join newsletter form.

## Support

You can order our support on [this page](https://bitbag.shop/products/sylius-mailchimp).

We work on amazing eCommerce projects on top of Sylius and other great Symfony based solutions, like eZ Platform, Akeneo or Pimcore.
Need some help or additional resources for a project? Write us an email on mikolaj.krol@bitbag.pl or visit
[our website](https://bitbag.shop/)! :rocket:

## Demo

We created a demo app with some useful use-cases of the plugin! Visit [demo.bitbag.shop](https://demo.bitbag.shop/en_US/products-list/t-shirts) to take a look at it.
The admin can be accessed under [demo.bitbag.shop/admin](https://demo.bitbag.shop/admin) link and `sylius: sylius` credentials.

## Installation
```bash
$ composer require bitbag/mailchimp-plugin
```

Add plugin dependencies to your `config/bundles.php` file:
```php
return [
    ...

    BitBag\SyliusMailChimpPlugin\BitBagSyliusMailChimpPlugin::class => ['all' => true],
];
```

Import routing **on top** of your `config/routes.yaml` file:
```yaml
# config/routes.yaml

bitbag_sylius_mailchimp_plugin:
    resource: "@BitBagSyliusMailChimpPlugin/Resources/config/routing.yml"
```

Configure MailChimp credentials
```yaml
# .env

...
MAIL_CHIMP_API_KEY=YOUR_KEY

MAIL_CHIMP_LIST_ID=YOUR_LIST_ID

```

Include the newsletter in your template:
```twig
{% include '@BitBagSyliusMailChimpPlugin/_subscribe.html.twig' %}
```

Install the assets
```bash
$ bin/console assets:install --symlink
```

Add these javascripts to the layout template that includes your subscription form imported in the previous steps
```html
<script src="{{ asset(path) }}"></script>
<script src="{{ asset('bundles/bitbagsyliusmailchimpplugin/bitbag-mailchimp-plugin-newsletter.js') }}"></script>
<script>
    $('#footer-newsletter-form').joinNewsletter();
</script>
```

That's the simplest and fastest way to integrate the jQuery plugin. If you need to customize it, simply take a look at
[bitbag-mailchimp-plugin-newsletter.js](src/Resources/public/bitbag-mailchimp-plugin-newsletter.js), create your own `*.js` plugin and 
import it in your main `Gulpfile.js`.

## Customization

### Available services you can [decorate](https://symfony.com/doc/current/service_container/service_decoration.html) and forms you can [extend](http://symfony.com/doc/current/form/create_form_type_extension.html)
```bash
$ bin/console debug:container | grep bitbag_sylius_mailchimp_plugin
```

### Parameters you can override in your parameters.yml(.dist) file
```bash
$ bin/console debug:container --parameters | grep bitbag
```

## Testing
```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn run gulp
$ bin/console assets:install public -e test
$ bin/console doctrine:schema:create -e test
$ bin/console server:run 127.0.0.1:8080 -d public -e test
$ mailChimp
$ open http://localhost:8080
$ vendor/bin/behat
$ vendor/bin/phpspec run
```

## Contribution

Learn more about our contribution workflow on http://docs.sylius.org/en/latest/contributing/.
