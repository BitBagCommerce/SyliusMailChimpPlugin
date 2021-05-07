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

## About us

At BitBag we do believe in open source. However, we are able to do it just beacuse of our awesome clients, who are kind enough to share some parts of our work with the community. Therefore, if you feel like there is a possibility for us working together, feel free to reach us out. You will find out more about our professional services, technologies and contact details at https://bitbag.io/.

## BitBag SyliusMailChimpPlugin

This plugin allows you to integrate MailChimp newsletter sign-in process with Sylius
platform through customer registration, account updates or footer/modal join newsletter form.

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

To get info about list id:
https://mailchimp.com/developer/marketing/api/lists/get-lists-info/


```yaml
# .env

...
MAIL_CHIMP_API_KEY=YOUR_KEY

MAIL_CHIMP_LIST_ID=YOUR_LIST_ID

MAIL_CHIMP_WEBHOOK_SECRET=QUERY_PARAMETER_FOR_UNSUBSCRIBED_WEBHHOOK
```

[Read more about MAIL_CHIMP_WEBHOOK_SECRET](#Configuring Unsubscribe webhook)

You can read more about Mailchimp webhooks here: https://mailchimp.com/developer/marketing/guides/sync-audience-data-webhooks/



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
<script src="{{ asset('path/to/jquery.js') }}"></script>
<script src="{{ asset('bundles/bitbagsyliusmailchimpplugin/bitbag-mailchimp-plugin-newsletter.js') }}"></script>
<script>
    $('#footer-newsletter-form').joinNewsletter();
</script>
```

That's the simplest and fastest way to integrate the jQuery plugin. If you need to customize it, simply take a look at
[bitbag-mailchimp-plugin-newsletter.js](src/Resources/public/bitbag-mailchimp-plugin-newsletter.js), create your own `*.js` plugin and 
import it in your main `Gulpfile.js`.

## Configuring Unsubscribe webhook

Configuring this options allows you to keep your database in sync with mailchimp if user will decide to resign from subscribing your mailing list through MailChimp link.

To create such a webhook on mailchimp side follow [this official mailchimp article](https://mailchimp.com/developer/marketing/guides/sync-audience-data-webhooks/)

Plugin ready endpoint is defined as follows:

```yaml
bitbag_sylius_mailchimp_plugin_webhook:
    path: /mailchimp/webhook
```

On webhook configuration on mailchimp use following Webhook URL:

`https://yourdomain/mailchimp/webhook/?qsecret=<WEBHOOK_SECRET>`

Used `WEBHOOK_SECRET` on Mailchimp side should be the same as `MAIL_CHIMP_WEBHOOK_SECRET` from .env file on Your project.

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
