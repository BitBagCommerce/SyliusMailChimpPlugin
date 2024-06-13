<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Controller;

use BitBag\SyliusMailChimpPlugin\Handler\NewsletterSubscriptionInterface;
use BitBag\SyliusMailChimpPlugin\Validator\NewsletterValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Contracts\Translation\TranslatorInterface;

final class NewsletterController
{
    /** @var NewsletterValidator */
    private $validator;

    /** @var TranslatorInterface */
    private $translator;

    /** @var NewsletterSubscriptionInterface */
    private $handler;

    /** @var CsrfTokenManager */
    private $tokenManager;

    public function __construct(
        NewsletterValidator $validator,
        TranslatorInterface $translator,
        NewsletterSubscriptionInterface $handler,
        CsrfTokenManager $tokenManager,
    ) {
        $this->validator = $validator;
        $this->translator = $translator;
        $this->handler = $handler;
        $this->tokenManager = $tokenManager;
    }

    public function subscribeAction(Request $request): JsonResponse
    {
        $email = $request->request->get('email');
        $token = $request->request->get('_token');

        if (!is_string($email) || !is_string($token)) {
            return new JsonResponse([
                'success' => false,
                'errors' => json_encode($this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.invalid_variable_type')),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $errors = $this->validator->validate($email);

            if (!$this->tokenManager->isTokenValid(new CsrfToken('newsletter', $token))) {
                $errors[] = $this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.invalid_csrf_token');
            }

            if (count($errors) === 0) {
                $this->handler->subscribe($email);

                return new JsonResponse([
                    'success' => true,
                    'message' => $this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.subscribed_successfully'),
                ]);
            }

            return new JsonResponse([
                'success' => false,
                'errors' => json_encode($errors),
            ], Response::HTTP_BAD_REQUEST);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse([
                'success' => false,
                'errors' => json_encode([$this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.unexpected_error')]),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
