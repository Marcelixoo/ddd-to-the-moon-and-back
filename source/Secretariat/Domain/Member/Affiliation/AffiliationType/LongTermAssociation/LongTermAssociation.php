<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationType\LongTermAssociation;

use League\Period\Period;
use Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationInterface;

class LongTermAssociation implements AffiliationInterface
{
    /** @var string */
    private $title;

    public function __construct(
        string $institutionId,
        Employment $employment,
        Period $period
    ) {
        $this->employment = $employment;
        $this->institutionId = $institutionId;
        $this->period = $period;
    }

    public static function fromPrimitives(
        string $institutionId,
        string $employment,
        string $startDate,
        string $endDate
    ): self {
        return new self(
            $institutionId,
            Employment::forTitle($employment),
            new Period($startDate, $endDate)
        );
    }

    public function title(): string
    {
        return $this->title;
    }

    public function institutionId(): string
    {
        return $this->institutionId;
    }

    public function period(): Period
    {
        return $this->period;
    }

    public function overlaps(AffiliationInterface $other): bool
    {
        return $this->period->overlaps($other->period());
    }
}