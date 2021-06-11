<?php

declare(strict_types=1);

namespace Atlas\DDD\Backoffice\Features\RegisterMember;

use Atlas\DDD\Backoffice\Model\Member;
use Atlas\DDD\Backoffice\Model\MembersRepositoryInterface;

final class MembersRegistrationService
{
    public function __construct(MembersRepositoryInterface $repository)
    {
        $this->membersRepository = $repository;
    }

    /** @return string Member identifier */
    public function register(NewMemberRequest $request): string
    {
        $this->membersRepository->checkEmailIsNotYetTaken($request->emailAddress());

        $newMember = new Member(
            $this->membersRepository->nextIdentity(),
            $request->institutionId(),
            $request->name(),
            $request->emailAddress()
        );

        $this->membersRepository->add($newMember);

        return $newMember->id();
    }
}