<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMailChimpPlugin\Model;

use Symfony\Component\HttpFoundation\Request;

/**
 * @see https://mailchimp.com/developer/guides/about-webhooks/
 */
final class WebhookData
{
    public const TYPE_SUBSCRIBE = 'subscribe';

    public const TYPE_UNSUBSCRIBE = 'unsubscribe'; //An event's action is either unsub or delete. The reason will be manual unless caused by a spam complaint, then it will be abuse.

    public const TYPE_PROFILE = 'profile'; //profile update

    public const TYPE_UPEMAIL = 'upemail'; //email change

    public const TYPE_CLEANED = 'cleaned';

    public const TYPE_CAMPAIGN = 'campaign';

    /** @var string */
    private $type;

    /** @var string */
    private $firedAt;

    /** @var array */
    private $data;

    public function __construct(
        string $type,
        string $firedAt,
        array $data,
    ) {
        $this->type = $type;
        $this->firedAt = $firedAt;
        $this->data = $data;
    }

    public static function createFromRequest(Request $request): self
    {
        $dateString = (new \DateTime())->format('Y-m-d H:i:s');

        return new self(
            $request->get('type', self::TYPE_UNSUBSCRIBE),
            $request->get('fired_at', $dateString),
            $request->get('data', []),
        );
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getFiredAt(): string
    {
        return $this->firedAt;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
