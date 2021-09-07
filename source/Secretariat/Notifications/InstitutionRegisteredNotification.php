<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Notifiers;

use Fence\Mailer;
use Fence\View\View;
use Atlas\DDD\Application\ProfileLinkGenerator\ProfileLinkGeneratorInterface;
use Atlas\DDD\Secretariat\Model\Institution\Institution;
use RuntimeException;
use Webmozart\Assert\Assert;

final class InstitutionRegistrationNotifier
{
    public function __construct(
        Notifier $notifier,
        View $templateEngine,
        ProfileLinkGeneratorInterface $profileLinkGenerator
    ) {
        $this->notifier = $notifier;
        $this->templateEngine = $templateEngine;
        $this->profileLinkGenerator = $profileLinkGenerator;
    }

    public function notify(Institution $institution): void
    {
        $messageBody = $this->createBodyFromTemplate($institution);
        $message = Draft::create()
            ->body($messageBody)
            ->subject("Institution registration")
            ->to("Atlas.Secretariat@cern.ch")
            ->composeMessage();

        $this->notifier->send($message);
    }

    private function createBodyFromTemplate(Institution $institution): string
    {
        return $this->templateEngine->generateRender(
            "notifications/institution-registered.html.twig", [
            'institution' => [
                'name' => $institution->name(),
                'country' => $institution->country(),
                'profile' => $this->profileLinkGenerator->forInstitution($institution->id()),
            ]
        ]);
    }
}

class Notifier
{
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(Message $message): void
    {
        $this->setRecipients($message);

        $this->mailer->set_subject($message->subject());
        $this->mailer->set_message($message->body());

        $this->mailer->fire();
    }

    private function setRecipients(Message $message): void
    {
        $this->mailer->resetRecipients();

        foreach ($message->to() as $to) {
            $this->mailer->add_recipient($to);
        }

        foreach ($message->cc() as $cc) {
            $this->mailer->add_cc($cc);
        }

        foreach ($message->bcc() as $bcc) {
            $this->mailer->add_bcc($bcc);
        }
    }
}

class Draft
{
    private function __construct()
    {
        $this->recipients = new ReceipientsDraft();
        $this->sender = Sender::default();
    }

    public static function create(): self
    {
        return new self();
    }

    public function body(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function subject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function replyTo(string $replyTo): self
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    public function to(string $to): self
    {
        $this->recipients->addTo($to);

        return $this;
    }

    public function cc(string $to): self
    {
        $this->recipients->addTo($to);

        return $this;
    }

    public function bcc(string $to): self
    {
        $this->recipients->addTo($to);

        return $this;
    }

    public function composeMessage(): Message
    {
        $this->assertBodyWasDefined();

        return new Message(
            $this->sender->asfinalSender(),
            $this->recipients->asFinalRecipients(),
            $this->subject,
            $this->body
        );
    }

    private function assertBodyWasDefined(): void
    {
        if (is_null($this->body)) {
            throw new MessageBodyNotDefined("Please provide a body for the message");
        }
    }
}

class MessageBodyNotDefined extends RuntimeException
{}

class Email
{
    public function __construct(string $address, string $alias = "")
    {
        $this->address = $address;
        $this->alias = $alias;
    }

    public function address(): string
    {
        return $this->address;
    }

    public function alias(): string
    {
        return $this->alias;
    }

    public function toString(): string
    {
        return "{$this->alias} <{$this->address}>";
    }

    public function __toString()
    {
        return $this->toString();
    }
}

class Sender
{
    public function __construct(string $from, string $replyTo)
    {
        $this->from = $from;
        $this->replyTo = $replyTo;
    }

    public static function default(): self
    {
        return new self(
            "developers@cern.ch",
            "noreply@cern.ch"
        );
    }

    public function from(): string
    {
        return $this->from;
    }

    public function replyTo(): string
    {
        return $this->replyTo;
    }
}

class ReceipientsDraft
{
    use AddRecipients;

    public function asFinalList(): ReceipientsList
    {
        $finalListOfRecipients = new ReceipientsList($this->ofTypeTo);

        foreach ($this->ofTypeCc as $cc) {
            $finalListOfRecipients->addCc($cc);
        }

        foreach ($this->ofTypeBcc as $bcc) {
            $finalListOfRecipients->addBcc($bcc);
        }

        return $finalListOfRecipients;
    }
}

class ReceipientsList
{
    use AddRecipients;

    public function __construct(Email ...$recipients)
    {
        Assert::minCount($recipients, 1);

        $withAddresAsKey = function ($carry, Email $email) {
            $carry[$email->address()] = $email;
            return $carry;
        };

        $this->ofTypeTo = array_reduce($recipients, $withAddresAsKey, []);
        $this->ofTypeCc = [];
        $this->ofTypeBcc = [];
    }
}

trait AddRecipients
{
    public function to(): array
    {
        return $this->ofTypeTo;
    }

    public function cc(): array
    {
        return $this->ofTypeCc;
    }

    public function bcc(): array
    {
        return $this->ofTypeBcc;
    }

    public function addTo(Email $email): self
    {
        $this->ofTypeTo[$email->address()] = $email;

        return $this;
    }

    public function addCc(Email $email): self
    {
        $this->ofTypeCc[$email->address()] = $email;

        return $this;
    }

    public function addBcc(Email $email): self
    {
        $this->ofTypeBcc[$email->address()] = $email;

        return $this;
    }
}

class Message
{
    public function __construct(
        Sender $sender,
        ReceipientsList $recipients,
        string $subject,
        string $body
    ) {
        $this->sender = $sender;
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function to(): array
    {
        return $this->recipients->to();
    }

    public function cc(): array
    {
        return $this->recipients->cc();
    }

    public function bcc(): array
    {
        return $this->recipients->bcc();
    }
}