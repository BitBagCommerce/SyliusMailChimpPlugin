<?php

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class NewsletterController extends FOSRestController
{
    public function subscribeAction(Request $request)
    {
        $email = $request->request->get('email');

        $validator = $this->get('bitbag_sylius_mailchimp_plugin.validator.email_validator');
        $errors = $validator->validate($email);

        if (!$this->isCsrfTokenValid('newsletter', $request->request->get('_token'))) {
            $errors[] = $this->get('translator')->trans('bitbag_sylius_mailchimp_plugin.ui.invalid_csrf_token');
        }

        if (count($errors) === 0) {
            $handler = $this->get('bitbag_sylius_mailchimp_plugin.handler.newsletter_subscription_handler');
            $handler->subscribe($email);

            return new JsonResponse([
                'success' => true,
                'message' => $this->get('translator')->trans('bitbag_sylius_mailchimp_plugin.ui.subscribed_successfully'),
            ]);
        }

        return new JsonResponse(['success' => false, 'errors' => json_encode($errors)], Response::HTTP_BAD_REQUEST);
    }
}
