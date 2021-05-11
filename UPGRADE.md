# UPGRADE FROM 1.2.1/1.1.1 to 1.2.2

Integration for mailchimp webhook was added ([read more here](doc/mailchimp_webhook.md)) along with new parameter: 
```yml
    mailchimp.webhook_secret: '%env(resolve:MAIL_CHIMP_WEBHOOK_SECRET)%'
```

Added more detailed error codes when mailchimp list id is not valid.