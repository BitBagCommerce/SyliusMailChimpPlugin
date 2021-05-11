<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on tomasz.grochowski@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Controller;

use BitBag\SyliusMailChimpPlugin\Handler\NewsletterSubscriptionHandler;
use BitBag\SyliusMailChimpPlugin\Validator\NewsletterValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Contracts\Translation\TranslatorInterface;

final class NewsletterController
{
    /** @var NewsletterValidator */
    private $validator;

    /** @var TranslatorInterface */
    private $translator;

    /** @var NewsletterSubscriptionHandler */
    private $handler;

    /** @var CsrfTokenManager */
    private $tokenManager;

    public function __construct(
        NewsletterValidator $validator,
        TranslatorInterface $translator,
        NewsletterSubscriptionHandler $handler,
        CsrfTokenManager $tokenManager
    ) {
        $this->validator = $validator;
        $this->translator = $translator;
        $this->handler = $handler;
        $this->tokenManager = $tokenManager;
    }

    public function subscribeAction(Request $request): JsonResponse
    {
        $email = $request->request->get('email');
        $errors = $this->validator->validate($email);

        if (!$this->tokenManager->isTokenValid(new CsrfToken('newsletter', $request->request->get('_token')))) {
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
    }
}
