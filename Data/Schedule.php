<?php

declare(strict_types = 1);

namespace Phpro\Scheduler\Data;

class Schedule
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $scheduleId;

    /**
     * @var string
     */
    private $jobCode;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string
     */
    private $scheduledAt;

    /**
     * @var string
     */
    private $executedAt;

    /**
     * @var string
     */
    private $finishedAt;

    /**
     * @var ?string
     */
    private $messages;

    /**
     * @var float
     */
    private $duration;

    public function __construct(
        string $status,
        int $scheduleId,
        string $jobCode,
        string $createdAt,
        string $scheduledAt,
        string $executedAt,
        string $finishedAt,
        float $duration,
        ?string $messages
    ) {
        $this->status = $status;
        $this->scheduleId = $scheduleId;
        $this->jobCode = $jobCode;
        $this->createdAt = $createdAt;
        $this->scheduledAt = $scheduledAt;
        $this->executedAt = $executedAt;
        $this->finishedAt = $finishedAt;
        $this->messages = $messages;
        $this->duration = $duration;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getScheduleId(): int
    {
        return $this->scheduleId;
    }

    public function getJobCode(): string
    {
        return $this->jobCode;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getScheduledAt(): string
    {
        return $this->scheduledAt;
    }

    public function getExecutedAt(): string
    {
        return $this->executedAt;
    }

    public function getFinishedAt(): string
    {
        return $this->finishedAt;
    }

    public function getMessages(): ?string
    {
        return $this->messages;
    }

    public function getDuration(): float
    {
        return $this->duration;
    }
}
