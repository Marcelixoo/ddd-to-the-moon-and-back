<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Features\RegisterMember;

use Atlas\DDD\Secretariat\Model\Member\Member;
use Atlas\DDD\Secretariat\Model\Member\MembersRepositoryInterface;

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
            $request->name(),
            $request->emailAddress(),
            $request->canonicalAffiliation()
        );

        $this->membersRepository->add($newMember);

        return $newMember->id();
    }
}