<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Controller;

use BitBag\SyliusMailChimpPlugin\Handler\NewsletterSubscriptionInterface;
use BitBag\SyliusMailChimpPlugin\Model\WebhookData;
use BitBag\SyliusMailChimpPlugin\Validator\WebhookValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MailchimpController
{
    /** @var WebhookValidator */
    private $validator;

    /** @var NewsletterSubscriptionInterface */
    private $handler;

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(
        WebhookValidator $validator,
        NewsletterSubscriptionInterface $handler,
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
        ]);
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
        $data = $webhookData->getData();
        if (array_key_exists('list_id', $data) && !$this->validator->isListIdValid($data['list_id'])) {
            $errors[] = $this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.invalid_list_id_malichimp');
        }

        return $errors;
    }
}
