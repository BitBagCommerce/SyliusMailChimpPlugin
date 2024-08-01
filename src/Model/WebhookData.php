<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
