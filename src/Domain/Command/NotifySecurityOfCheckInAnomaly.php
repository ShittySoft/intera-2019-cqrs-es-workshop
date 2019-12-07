<?php

declare(strict_types=1);

namespace Building\Domain\Command;

use Prooph\Common\Messaging\Command;
use Rhumsaa\Uuid\Uuid;

/**
 * Note: this command should reside in a different domain (security-pertinent).
 * We put it in the "Building" domain to avoid having to move all files around.
 */
final class NotifySecurityOfCheckInAnomaly extends Command
{
    /** @var Uuid */
    private $building;
    /** @var string */
    private $username;

    private function __construct(Uuid $building, string $username)
    {
        $this->init();

        $this->building = $building;
        $this->username = $username;
    }

    public static function inBuilding(Uuid $building, string $username) : self
    {
        return new self($building, $username);
    }

    public function building() : Uuid
    {
        return $this->building;
    }

    public function username() : string
    {
        return $this->username;
    }

    /**
     * {@inheritDoc}
     */
    public function payload() : array
    {
        return [
            'building' => $this->building->toString(),
            'username' => $this->username,
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function setPayload(array $payload)
    {
        $this->building = Uuid::fromString($payload['building']);
        $this->username = $payload['username'];
    }
}
