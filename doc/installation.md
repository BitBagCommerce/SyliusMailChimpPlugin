
## Installation

Please require plugin by running composer command:

```bash
$ composer require bitbag/mailchimp-plugin --no-scripts
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

Add plugin parameters to your `config/services.yaml` file:
```yaml
# config/services.yaml

parameters:
  ...
  mailchimp.api_key: '%env(resolve:MAIL_CHIMP_API_KEY)%'
  mailchimp.list_id: '%env(resolve:MAIL_CHIMP_LIST_ID)%'
  mailchimp.webhook_secret: '%env(resolve:MAIL_CHIMP_WEBHOOK_SECRET)%'
```

Configure MailChimp credentials

To get info about list id:
https://mailchimp.com/developer/marketing/api/lists/get-lists-info/


```yaml
# .env.local

// ...

MAIL_CHIMP_API_KEY=YOUR_KEY
MAIL_CHIMP_LIST_ID=YOUR_LIST_ID
MAIL_CHIMP_WEBHOOK_SECRET=QUERY_PARAMETER_FOR_UNSUBSCRIBED_WEBHHOOK
```

[Read more about MAIL_CHIMP_WEBHOOK_SECRET](mailchimp_webhook.md)

You can read more about Mailchimp webhooks here: https://mailchimp.com/developer/marketing/guides/sync-audience-data-webhooks/

Add the parameters listed below to your `config/packages/_sylius.yaml` file:

```
parameters:
    mailchimp.api_key: '%env(resolve:MAIL_CHIMP_API_KEY)%'
    mailchimp.list_id: '%env(resolve:MAIL_CHIMP_LIST_ID)%'
    mailchimp.webhook_secret: '%env(resolve:MAIL_CHIMP_WEBHOOK_SECRET)%'
```

Import plugin's `webpack.config.js` file

```js
// webpack.config.js
const [bitbagMailChimp] = require('./vendor/bitbag/mailchimp-plugin/webpack.config.js');
...

module.exports = [..., bitbagMailChimp];
```

Configure config/packages/webpack_encore.yaml
.yaml
```yaml
webpack_encore:
    builds:
        // ...
        shop: '%kernel.project_dir%/public/build/shop'
        admin: '%kernel.project_dir%/public/build/admin'
        mail-chimp-shop: '%kernel.project_dir%/public/build/bitbag/mail-chimp/shop'
```

Include the newsletter in your template:
```twig
{% include '@BitBagSyliusMailChimpPlugin/_subscribe.html.twig' %}
```

Add these javascripts to the layout template that includes your subscription form imported in the previous steps
```html

{{ encore_entry_script_tags('bitbag-mail-chimp-shop', null, 'mail-chimp-shop') }}
<script>
    document.addEventListener("DOMContentLoaded", function(event) { 
        $('#footer-newsletter-form').joinNewsletter();
    });
</script>
```

Clear project cache:
```php
bin/console cache:clear # if there is an issue with translations displaying correctly, clear the cache again.
```

Update your webpack build:

```bash
yarn encore dev # or yarn encore prod, when you build production environment
```
