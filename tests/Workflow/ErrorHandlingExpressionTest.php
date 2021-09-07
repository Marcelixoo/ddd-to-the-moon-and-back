<?php

declare(strict_types=1);

namespace Atlas\DDD\Tests\Workflow;

use PHPUnit\Framework\TestCase;

class ErrorHandlingExpressionTest extends TestCase
{
    /** @test */
    public function any_error_stops_processing_further_statements(): void
    {
        new ErrorHandlingExpressions()
    }

}