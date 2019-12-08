<?php

declare(strict_types=1);

namespace Specification;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Building\Domain\Aggregate\Building;
use Building\Domain\DomainEvent\NewBuildingWasRegistered;
use Building\Domain\DomainEvent\UserCheckedIn;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\Aggregate\AggregateType;
use Rhumsaa\Uuid\Uuid;
use Webmozart\Assert\Assert;

final class CheckInCheckOut implements Context
{
    /** @var AggregateChanged[] */
    private $pastHistory = [];

    /** @var Building|null */
    private $building;

    /** @var AggregateChanged[]|null */
    private $recordedEvents;

    /** @Given /^a building has been registered$/ */
    public function a_building_has_been_registered() : void
    {
        $this->pastHistory[] = NewBuildingWasRegistered::withName(
            Uuid::uuid4(),
            'A name for a building'
        );
    }

    /** @When /^"([^"]*)" checks into the building$/ */
    public function checksIntoTheBuilding(string $username) : void
    {
        $this->building()->checkInUser($username);
    }

    /** @Then /^"([^"]*)" should have been checked into the building$/ */
    public function shouldHaveBeenCheckedIntoTheBuilding(string $username) : void
    {
        $event = $this->popNextRecordedEvent();

        Assert::isInstanceOf($event, UserCheckedIn::class);
        Assert::same($event->username(), $username);
    }

    private function building() : Building
    {
        if (null !== $this->building) {
            return $this->building;
        }

        return $this->building = (new AggregateTranslator())
            ->reconstituteAggregateFromHistory(
                AggregateType::fromAggregateRootClass(Building::class),
                new \ArrayIterator($this->pastHistory)
            );
    }

    private function popNextRecordedEvent() : AggregateChanged
    {
        if (null !== $this->recordedEvents) {
            return array_shift($this->recordedEvents);
        }

        $this->recordedEvents = (new AggregateTranslator())
            ->extractPendingStreamEvents($this->building());

        return array_shift($this->recordedEvents);
    }

}
