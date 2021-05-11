## Configuring Unsubscribe webhook
***

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
