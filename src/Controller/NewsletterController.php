<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Controller;

use BitBag\SyliusMailChimpPlugin\Handler\NewsletterSubscriptionHandler;
use BitBag\SyliusMailChimpPlugin\Validator\NewsletterValidator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    public function subscribeAction(Request $request, SessionInterface $session): RedirectResponse
    {
        $email = $request->request->get('email');
        $token = $request->request->get('_token');

        if (!is_string($email) || !is_string($token)) {
            /**@phpstan-ignore-next-line */
            $session->getFlashBag()->add('error', $this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.invalid_variable_type'));
            return new RedirectResponse($request->headers->get('referer'));
        }

        $errors = $this->validator->validate($email);

        if (!$this->tokenManager->isTokenValid(new CsrfToken('newsletter', $token))) {
            $errors[] = $this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.invalid_csrf_token');
        }

        if (count($errors) === 0) {
            $this->handler->subscribe($email);

            if ($this->handler::$isValidEmail) {
                /**@phpstan-ignore-next-line */
                $session->getFlashBag()->add('success', $this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.subscribed_successfully'));
                return new RedirectResponse($request->headers->get('referer'));
            }
        }
        /**@phpstan-ignore-next-line */
        $session->getFlashBag()->add('error', $this->translator->trans('bitbag_sylius_mailchimp_plugin.ui.invalid_email_variable_type'));
        return new RedirectResponse($request->headers->get('referer'));
    }
}
