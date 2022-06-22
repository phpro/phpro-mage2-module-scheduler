<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Factory\Schedule;

use Magento\Cron\Model\Schedule;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class ScheduleFactory
{
    private const TIME_FORMAT = '%Y-%m-%d %H:%M:00';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var DateTime
     */
    private $dateTime;

    public function __construct(
        ObjectManagerInterface $objectManager,
        DateTime $dateTime
    ) {
        $this->objectManager = $objectManager;
        $this->dateTime = $dateTime;
    }

    public function create(array $data = []): Schedule
    {
        $schedule = $this->objectManager->create(
            Schedule::class,
            $data
        );
        $now = $this->dateTime->gmtTimestamp();
        $schedule->setCreatedAt(date(self::TIME_FORMAT, $now));
        $schedule->setScheduledAt(date(self::TIME_FORMAT, $now + 60));

        return $schedule;
    }
}
