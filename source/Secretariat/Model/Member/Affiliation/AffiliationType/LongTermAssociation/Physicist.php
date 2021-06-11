<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationType\LongTermAssociation;


class Physicist extends Employment
{
    public const EMPLOYMENT_TITLE = "physicist";

    public function __construct()
    {
        parent::__construct(self::EMPLOYMENT_TITLE);
    }

    public function allowsInstitutionalRepresentation(): bool
    {
        return true;
    }
}