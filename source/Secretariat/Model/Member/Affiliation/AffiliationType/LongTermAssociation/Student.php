<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationType\LongTermAssociation;

class Student extends Employment
{
    public const EMPLOYMENT_TITLE = "student";

    public function __construct()
    {
        parent::__construct(self::EMPLOYMENT_TITLE);
    }

    public function allowsInstitutionalRepresentation(): bool
    {
        return false;
    }
}