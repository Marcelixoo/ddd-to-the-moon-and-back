<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationType\LongTermAssociation;

use InvalidArgumentException;

abstract class Employment
{
    /** @var string */
    private $title;

    protected function __construct(string $title)
    {
        $this->title = $title;
    }

    public static function forTitle(string $title): Employment
    {
        switch($title) {
            case Student::EMPLOYMENT_TITLE:
                return new Student();
                break;
            case Physicist::EMPLOYMENT_TITLE:
                return new Physicist();
                break;
            default:
                throw new InvalidArgumentException(
                    "Could not recognize employment title {$title}."
                );
                break;
        }
    }

    public function title(): string
    {
        return $this->title;
    }
}