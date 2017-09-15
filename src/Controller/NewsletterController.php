<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\MailChimpPlugin\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class NewsletterController extends FOSRestController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function subscribeAction(Request $request): JsonResponse
    {
        $email = $request->request->get('email');

        $validator = $this->get('bitbag_mailchimp_plugin.validator.email_validator');
        $errors = $validator->validate($email);

        if (!$this->isCsrfTokenValid('newsletter', $request->request->get('_token'))) {
            $errors[] = $this->get('translator')->trans('bitbag.mailchimp_plugin.invalid_csrf_token');
        }

        if (count($errors) === 0) {
            $handler = $this->get('bitbag_mailchimp_plugin.handler.newsleter_subscription_handler');
            $handler->subscribe($email);
            return new JsonResponse([
                'success' => true,
                'message' => $this->get('translator')->trans('bitbag.mailchimp_plugin.subscribed_successfully')
            ]);
        }

        return new JsonResponse(['success' => false, 'errors' => json_encode($errors)], Response::HTTP_BAD_REQUEST);
    }
}