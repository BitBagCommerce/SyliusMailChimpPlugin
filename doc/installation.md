# Installation

## Overview:
GENERAL
- [Requirements](#requirements)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
---
FRONTEND
- [Templates](#templates)
- [Webpack](#webpack)
---
ADDITIONAL
- [Additional configuration](#additional-configuration)
- [Known Issues](#known-issues)
---

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version         |
|---------------|-----------------|
| PHP           | \>=8.1          |
| sylius/sylius | 1.12.x - 1.13.x |
| MySQL         | \>= 5.7         |
| NodeJS        | \>= 18.x        |

## Composer:
```bash
composer require bitbag/mailchimp-plugin --no-scripts
```

## Basic configuration:
Add plugin dependencies to your `config/bundles.php` file:

```php
# config/bundles.php

return [
    ...
    BitBag\SyliusMailChimpPlugin\BitBagSyliusMailChimpPlugin::class => ['all' => true],
];
```

Import routing in your `config/routes.yaml` file 
(may have already been added in the `config/routes/bitbag_sylius_mailchimp_plugin.yaml` file):

```yaml
# config/routes.yaml

bitbag_sylius_mailchimp_plugin:
    resource: "@BitBagSyliusMailChimpPlugin/Resources/config/routing.yml"
```

Add the parameters listed below to your `config/packages/_sylius.yaml` file:
```yaml
# config/packages/_sylius.yaml

parameters:
    mailchimp.api_key: '%env(resolve:MAIL_CHIMP_API_KEY)%'
    mailchimp.list_id: '%env(resolve:MAIL_CHIMP_LIST_ID)%'
    mailchimp.webhook_secret: '%env(resolve:MAIL_CHIMP_WEBHOOK_SECRET)%'
```

Add plugin parameters to your `config/services.yaml` file:
```yaml
# config/services.yaml

parameters:
  ...
  mailchimp.api_key: '%env(resolve:MAIL_CHIMP_API_KEY)%'
  mailchimp.list_id: '%env(resolve:MAIL_CHIMP_LIST_ID)%'
  mailchimp.webhook_secret: '%env(resolve:MAIL_CHIMP_WEBHOOK_SECRET)%'
```

### Configure MailChimp credentials:

To get info about list id: https://mailchimp.com/developer/marketing/api/lists/get-lists-info/

```dotenv
# .env.local

...
MAIL_CHIMP_API_KEY=YOUR_KEY
MAIL_CHIMP_LIST_ID=YOUR_LIST_ID
MAIL_CHIMP_WEBHOOK_SECRET=QUERY_PARAMETER_FOR_UNSUBSCRIBED_WEBHHOOK
```

- [Read more about MAIL_CHIMP_WEBHOOK_SECRET](https://github.com/BitBagCommerce/SyliusMailChimpPlugin/blob/master/doc/mailchimp_webhook.md)

You can read more about Mailchimp webhooks here: https://mailchimp.com/developer/marketing/guides/sync-audience-data-webhooks/

### Clear application cache by using command:
```bash
bin/console cache:clear
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.


## Templates
Include the newsletter in your template:
```php
# templates location: templates/bundles/SyliusShopBundle/ #

{% include '@BitBagSyliusMailChimpPlugin/_subscribe.html.twig' %}
```

You could, for example, overwrite the template `_newsletter.html.twig` and paste in place of the current form in this file.
```
vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/Resources/views/Homepage/_newsletter.html.twig
```

```php
// templates/bundles/SyliusShopBundle/Homepage/_newsletter.html.twig
// ..
<div class="column">
    {% include '@BitBagSyliusMailChimpPlugin/_subscribe.html.twig' %}
</div>
// ..
```

## Webpack
### Webpack.config.js

Please setup your `webpack.config.js` file to require the plugin's webpack configuration. To do so, please put the line below somewhere on top of your webpack.config.js file:
```js
const [bitbagMailChimp] = require('./vendor/bitbag/mailchimp-plugin/webpack.config.js');
```
As next step, please add the imported consts into final module exports:
```js
module.exports = [..., bitbagMailChimp];
```

### Webpack Encore
Add the webpack configuration into `config/packages/webpack_encore.yaml`:

```yaml
webpack_encore:
    output_path: '%kernel.project_dir%/public/build/default'
    builds:
        ...
        mail-chimp-shop: '%kernel.project_dir%/public/build/bitbag/mail-chimp/shop'
```

### Encore functions
Add encore functions to your templates:

SyliusShopBundle:
```php
{# @SyliusShopBundle/_scripts.html.twig #}
{{ encore_entry_script_tags('bitbag-mail-chimp-shop', null, 'mail-chimp-shop') }}
<script>
    document.addEventListener("DOMContentLoaded", function(event) { 
        $('#footer-newsletter-form').joinNewsletter();
    });
</script>
```

### Run commands
```bash
yarn install
yarn encore dev # or prod, depends on your environment
```

## Known issues
### Translations not displaying correctly
For incorrectly displayed translations, execute the command:
```bash
bin/console cache:clear
```
