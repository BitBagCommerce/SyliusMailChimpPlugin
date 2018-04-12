![BitBag](https://bitbag.pl/static/bitbag-logo.png)

## Overview

This plugin allows you to integrate MailChimp newsletter sign-in process with Sylius platform through customer registration, account updates or footer/modal join newsletter form.

## Support

Do you want us to customize this plugin for your specific needs? Write us an email on mikolaj.krol@bitbag.pl 💻

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
