<?php

declare(strict_types=1);

namespace Atlas\DDD\Tests\Secretariat;

use RuntimeException;
use InvalidArgumentException;
use League\Period\Datepoint;
use League\Period\Period;
use Webmozart\Assert\Assert;
use Atlas\DDD\Secretariat\Model\CernEmail;

class ActiveMember
{
    private function __construct(
        string $identifier,
        CernEmail $emailAddress
    ) {
        $this->id = $identifier;
        $this->emailAddress = $emailAddress;
        $this->affiliations = new Affiliations([]);
    }

    public static function withInstitutionalAffiliation(
        string $memberId,
        CernEmail $emailAddress,
        string $institutionId,
        Employment $employment
    ): self {
        $member = new self($memberId, $emailAddress);

        $firstAffiliation = new LongTermAffiliation($institutionId, $memberId, $employment);

        if ($firstAffiliation->isNotActiveNow()) {
            throw new InvalidArgumentException(
                "The first affiliation must be active at the time of registration."
            );
        }

        $member->addAffiliation($firstAffiliation);

        return $member;
    }

    public function addAffiliation(Affiliation $affiliation): void
    {
        $this->affiliations->add($affiliation);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function emailAddress(): CernEmail
    {
        return $this->emailAddress;
    }

    public function activeAffiliation(): Affiliation
    {
        $activeAffiliation = $this->affiliations->activeAffiliation();

        if (is_null($activeAffiliation)) {
            throw new RuntimeException("No active affiliations found for member with id {$this->id}");
        }

        return $activeAffiliation;
    }
}

class OverlappingAffiliationPeriod extends InvalidArgumentException
{
}

class Affiliations
{
    public function __construct(array $affiliations)
    {
        Assert::allIsInstanceOf($affiliations, Affiliation::class);

        $this->affiliations = $affiliations;
    }

    public function add(Affiliation $newAffiliation): void
    {
        foreach ($this->affiliations as $affiliation) {
            if ($affiliation->period()->overlaps($newAffiliation->period())) {
                throw new OverlappingAffiliationPeriod(
                    "New period {$newAffiliation->period()->format('Y-m-d')} overlaps with existent affiliation period."
                );
            }
        }

        $this->affiliations[] = $newAffiliation;
    }


    public function activeAffiliation(): Affiliation
    {
        foreach ($this->affiliations as $affiliation) {
            if ($affiliation->isActiveNow()) {
                return $affiliation;
            }
        }

        throw new RuntimeException("No active affiliations found for member with id {$this->id}");
    }
}

abstract class Affiliation
{
    public abstract function allowsInstitutionalRepresentation(): bool;
    public abstract function period(): Period;
    public abstract function isActiveNow(): bool;
    public abstract function isNotActiveNow(): bool;
}

class LongTermAffiliation extends Affiliation
{
    public function __construct(string $institutionId, string $memberId, Employment $employment)
    {
        $this->memberId = $memberId;
        $this->institutionId = $institutionId;
        $this->employment = $employment;
    }

    public function isActiveNow(): bool
    {
        return (Datepoint::create("now"))->isDuring($this->employment->period());
    }

    public function isNotActiveNow(): bool
    {
        return ! $this->isActiveNow();
    }

    public function allowsInstitutionalRepresentation(): bool
    {
        return $this->employment->canBecomeInstitutionRepresentative();
    }

    public function period(): Period
    {
        return $this->employment->period();
    }
}

abstract class Employment
{
    protected function __construct(string $title, Period $period)
    {
        $this->title = $title;
        $this->period = $period;
    }

    public function needsQualificationTask(): bool
    {
        return true;
    }

    public static function fromTitleAndDates(string $title, string $startDate, string $endDate): Employment
    {
        switch($title) {
            case Physicist::TITLE:
                return Physicist::forPeriod(new Period($startDate, $endDate));
                break;
            default:
                throw new InvalidArgumentException("Invalid employment title {$title}");
                break;
        }
    }

    public function period(): Period
    {
        return $this->period;
    }

    public abstract function canBecomeInstitutionRepresentative(): bool;
}

class Physicist extends Employment
{
    public const TITLE = "physicist";

    public static function forPeriod(Period $period): self
    {
        return new self(self::TITLE, $period);
    }

    public function canBecomeInstitutionRepresentative(): bool
    {
        return true;
    }
}

class Student extends Employment
{
    public const TITLE = "student";

    public static function forPeriod(Period $period): self
    {
        return new self(self::TITLE, $period);
    }

    public function canBecomeInstitutionRepresentative(): bool
    {
        return false;
    }
}

class Engineer extends Employment
{
    public const TITLE = "enginneer";

    public static function forPeriod(Period $period): self
    {
        return new self(self::TITLE, $period);
    }

    public function canBecomeInstitutionRepresentative(): bool
    {
        return false;
    }
}