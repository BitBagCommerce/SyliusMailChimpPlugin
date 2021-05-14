<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Controller;

use BitBag\SyliusMailChimpPlugin\Handler\NewsletterSubscriptionHandler;
use BitBag\SyliusMailChimpPlugin\Model\WebhookData;
use BitBag\SyliusMailChimpPlugin\Validator\WebhookValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MailchimpController
{
    /** @var WebhookValidator */
    private $validator;

    /** @var NewsletterSubscriptionHandler */
    private $handler;

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(
        WebhookValidator $validator,
        NewsletterSubscriptionHandler $handler,
        TranslatorInterface $translator
    ) {
        $this->validator = $validator;
        $this->handler = $handler;
        $this->translator = $translator;
    }

    public function webhookAction(Request $request): JsonResponse
    {
        $webhookData = WebhookData::createFromRequest($request);
        $errors = $this->validateRequest($webhookData, $request);

        if (count($errors) === 0) {
            $this->handler->unsubscribeCustomerFromLocalDatabase($webhookData->getData()['email']);

            return new JsonResponse([
                'success' => true,
                'message' => $this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.unsubscribed_successfully'),
            ]);
        }

        return new JsonResponse([
            'success' => false,
            'errors' => json_encode($errors),
        ], Response::HTTP_BAD_REQUEST);
    }

    private function validateRequest(WebhookData $webhookData, Request $request): array
    {
        $errors = $this->validator->validate($webhookData);
        if (!$this->validator->isRequestValid($request)) {
            $errors[] = $this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.invalid_query_secret');
        }
        if (WebhookData::TYPE_UNSUBSCRIBE !== $webhookData->getType()) {
            $errors[] = $this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.invalid_webhook_type');
        }
        if (!$this->validator->isListIdValid($webhookData->getData()['list_id'])) {
            $errors[] = $this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.invalid_list_id_malichimp');
        }

        return $errors;
    }
}
