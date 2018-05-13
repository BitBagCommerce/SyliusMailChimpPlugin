![BitBag](https://bitbag.pl/static/bitbag-logo.png)

## Overview

This plugin allows you to integrate MailChimp newsletter sign-in process with Sylius platform through customer registration, account updates or footer/modal join newsletter form.

## Support

We work on amazing eCommerce projects on top of Sylius and Pimcore. Need some help or additional resources for a project?
Write us an email on mikolaj.krol@bitbag.pl or visit [our website](https://bitbag.shop/)! :rocket:

## Demo

We created a demo app with some useful use-cases of the plugin! Visit [demo.bitbag.shop](https://demo.bitbag.shop) to take a look at it. 
The admin can be accessed under [demo.bitbag.shop/admin](https://demo.bitbag.shop/admin) link and `sylius: sylius` credentials.

## Installation

```bash
$ composer require bitbag/mailchimp-plugin

```
    
Import routing in your routing.yml file:

```yml
bitbag_sylius_mailchimp_plugin:
    resource: "@BitBagSyliusMailChimpPlugin/Resources/config/routing.yml"
    prefix: /
```
    
Add plugin dependencies to your AppKernel.php

```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...
        
        new \BitBag\SyliusMailChimpPlugin\BitBagSyliusMailChimpPlugin(),
    ]);
}
```

## Usage

Add MailChimp API key and default list ID to your parameters.yml file

```yml
parameters:
    ...
    
    mailchimp.api_key: YOUR_API_KEY
    mailchimp.list_id: DEFAULT_LIST_ID
 ```

In your twig template include 

```twig
{% include '@BitBagSyliusMailChimpPlugin/_subscribe.html.twig' %}
```

In case you'd like to submit the form with AJAX

1. Install assets  

```bash
$ bin/console assets:install --symlink
```

2. Override default sylius javascript template

```html
<script src="{{ asset(path) }}"></script>
<script src="{{ asset('bundles/mailchimpplugin/bitbag-mailchimp-plugin-newsletter.js') }}"></script>
<script>
    $('#footer-newsletter-form').joinNewsletter();
</script>
```

That's the simplest and fastest way to integrate the jQuery plugin. If you need to customize it, simply take a look at   
`bitbag-mailchimp-plugin-newsletter.js`, create your own `*.js` plugin and import it in your main `Gulpfile.js`.

## Testing

In order to run tests, execute following commands:

```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn run gulp
$ bin/console doctrine:database:create --env test
$ bin/console doctrine:schema:create --env test
$ vendor/bin/behat
$ vendor/bin/phpunit
$ vendor/bin/phpspec
```
## Contribution

Learn more about our contribution workflow on http://docs.sylius.org/en/latest/contributing/
