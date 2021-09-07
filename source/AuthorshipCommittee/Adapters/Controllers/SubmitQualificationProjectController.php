<?php

declare(strict_types=1);

namespace Atlas\DDD\AuthorshipCommittee\Adapters\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Webmozart\Assert\Assert;
use Atlas\DDD\Authorization\AuthenticatedUser;
use InvalidArgumentException;
use RuntimeException;
use stdClass;

final class SubmitQualificationProjectController
{
    public function __construct(AuthenticatedUser $authenticatedUser)
    {
        Assert::isInstanceOf($authenticatedUser, TeamLeader::class);

        $this->authenticatedUser = $authenticatedUser;
    }

    public function process(Request $request): Response
    {
        $body = json_decode($request->getBody());

        $workflow = new BusinessWorkflow(
            new CheckAuthenticatedUserIsTeamLeaderOfTargetInstitution(),
            new SubmitQualificationProjectForApproval(),
            new NotityNewQualificationProjectRequestByEmail(),
            new UpdateEmploymentEndDateBasedOnQualificationProjectStartDate(),
        );

        $result = $workflow->process(
            new QualificationProjectRequest(
                $this->authenticatedUser,
                $body->institutionId,
                $body->memberId,
                $body->projectDescrition,
                $body->proposedStartDate
            )
        );

        $this->messageBus->notifyResults($result);

        if ($result->isFailure()) {
            return Response::badRequest($result);
        }

        return Response::success($result);
    }
}

interface BusinessAction
{
    public function process(BusinessWorkflowRequest $request): BusinessWorkflowResult;
}

class BusinessWorkflow
{
    public function __construct(BusinessAction ...$actions)
    {
        $this->actions = $actions;
    }

    public function process(BusinessWorkflowRequest $request): BusinessWorkflowResult
    {
        foreach ($this->actions as $action) {
            $result = $action->process($request);

            if ($result->isFailure()) {
                return $result;
            }

            $request = $result->asNextInput();
        }

        $result;
    }
}

class BusinessWorkflowResult
{
    private $isFailure = false;
    private $payload = [];

    private function __construct(bool $isFailure, array $payload = [])
    {
        $this->isFailure = $isFailure;
        $this->payload = $payload;
    }

    public function asNextInput(): BusinessWorkflowRequest
    {
        if ($this->isFailure) {
            throw new RuntimeException("Trying to pipe failure onto the pipeline");
        }

        return new BusinessWorkflowRequest($this->payload);
    }
}

class BusinessWorkflowRequest
{
    public function __construct(array $body)
    {
        $this->body = $body;
    }

    public static function fromHttpRequest(Request $request): self
    {
        return new self(json_decode($request->getBody(), true));
    }

    public function asArray(): array
    {
        return $this->body;
    }

    public function asObject(): stdClass
    {
        return json_decode(json_encode($this->body), JSON_THROW_ON_ERROR);
    }

    public function hasKey($key): bool
    {
        return array_key_exists($key, $this->body);
    }

    public function __get($key)
    {
        if ($this->hasKey($key)) {
            return $this->body[$key];
        }

        throw new InvalidArgumentException("Property {$key} does not exist.");
    }

    public function __set($key, $value)
    {
        $this->body[$key] = $value;
    }
}
