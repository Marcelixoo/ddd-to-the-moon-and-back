<?php

declare(strict_types=1);

namespace Atlas\DDD\Tests\Testing;

use PHPUnit\Framework\TestCase;
use Guzzle\Http\Client;

/** @link https://gist.github.com/DavertMik/7969053 */
class EmailTestCase extends TestCase
{
    /** @var Client */
    private $mailcatcher;

    protected function setUp(): void
    {
        $this->mailcatcher = new Client('http://mailcatcher:1080');
        $this->cleanMessages();
    }

    protected function tearDown(): void
    {
        // $this->cleanMessages();
    }

    public function cleanMessages()
    {
        $this->mailcatcher->delete('/messages')->send();
    }

    public function getLastMessage()
    {
        $messages = $this->getMessages();
        if (empty($messages)) {
            $this->fail("No messages received");
        }
        // messages are in descending order
        return reset($messages);
    }

    public function getMessages()
    {
        $jsonResponse = $this->mailcatcher->get('/messages')->send();
        return json_decode((string) $jsonResponse->getBody());
    }

    // assertions
    public function assertEmailIsSent($description = '')
    {
        $this->assertNotEmpty($this->getMessages(), $description);
    }

    public function assertEmailSubjectContains($needle, $email, $description = '')
    {
        $this->assertStringContainsString($needle, $email->subject, $description);
    }

    public function assertEmailSubjectEquals($expected, $email, $description = '')
    {
        $this->assertStringContainsString($expected, $email->subject, $description);
    }

    public function assertEmailHtmlContains($needle, $email, $description = '')
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.html")->send();
        $this->assertStringContainsString($needle, (string) $response->getBody(), $description);
    }

    public function assertEmailTextContains($needle, $email, $description = '')
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.plain")->send();
        $this->assertStringContainsString($needle, (string) $response->getBody(), $description);
    }

    public function assertEmailSenderEquals($expected, $email, $description = '')
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.json")->send();
        $email = json_decode((string) $response->getBody());
        $this->assertEquals($expected, $email->sender, $description);
    }

    public function assertEmailRecipientsContain($needle, $email, $description = '')
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.json")->send();
        $email = json_decode((string) $response->getBody());
        $this->assertStringContainsString($needle, $email->recipients, $description);
    }
}