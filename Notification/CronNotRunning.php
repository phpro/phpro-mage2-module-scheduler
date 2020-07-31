<?php
declare(strict_types = 1);

namespace Phpro\Scheduler\Notification;

use Magento\Cron\Model\Schedule;
use Magento\Cron\Model\ResourceModel\Schedule\Collection;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Notification\MessageInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Phpro\Scheduler\Util\DateTimeConverter;

class CronNotRunning implements MessageInterface
{
    const CRON_IDLE_PERIOD_IN_SECONDS = 600; // 10 minutes
    const MESSAGE_IDENTITY = 'phpro_scheduler_cron_not_running';

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var Collection
     */
    private $scheduleCollection;

    /**
     * @var DateTimeConverter
     */
    private $converter;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    public function __construct(
        AuthorizationInterface $authorization,
        Collection $scheduleCollection,
        DateTimeConverter $converter,
        TimezoneInterface $timezone
    ) {
        $this->authorization = $authorization;
        $this->scheduleCollection = $scheduleCollection;
        $this->converter = $converter;
        $this->timezone = $timezone;
    }

    public function getIdentity(): string
    {
        return self::MESSAGE_IDENTITY;
    }

    public function isDisplayed(): bool
    {
        if (!$this->authorization->isAllowed('Phpro_Scheduler::schedule')) {
            return false;
        }
        /** @var Schedule $schedule */
        $schedule = $this->scheduleCollection->getLastItem();
        $secondsSinceLastJob = $this->converter->toTimestamp('now')
            - $this->converter->toTimestamp($schedule->getCreatedAt());

        return ($secondsSinceLastJob > self::CRON_IDLE_PERIOD_IN_SECONDS);
    }

    public function getText(): Phrase
    {
        /** @var Schedule $schedule */
        $schedule = $this->scheduleCollection->getLastItem();

        return __(sprintf(
            'The most recent cron job was created at <strong>%s</strong>. Make sure the Magento Cron is running',
            $this->timezone->date(new \DateTime($schedule->getCreatedAt()))->format('Y-m-d H:i:s')
        ));
    }

    public function getSeverity(): int
    {
        return self::SEVERITY_CRITICAL;
    }
}
