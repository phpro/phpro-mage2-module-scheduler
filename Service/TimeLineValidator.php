<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Service;

use Magento\Cron\Model\Schedule;
use Phpro\Scheduler\Config\CronConfiguration;
use Phpro\Scheduler\Config\Source\TimelineLimit;
use Phpro\Scheduler\Util\DateTimeConverter;

class TimeLineValidator
{
    /**
     * @var CronConfiguration
     */
    private $config;

    /**
     * @var DateTimeConverter
     */
    private $converter;

    public function __construct(CronConfiguration $config, DateTimeConverter $converter)
    {
        $this->config = $config;
        $this->converter = $converter;
    }

    public function validate(Schedule $schedule): bool
    {
        if (!$schedule->getScheduledAt()) {
            return false;
        }

        $limit = $this->config->getTimelineLimit();
        if (TimelineLimit::LIMIT_0 === $limit) {
            return true;
        }

        $limit = $this->converter->toCurrentTimestamp() - ((int) $limit * 3600);
        $scheduleAt = $this->converter->toTimestamp($this->converter->convertDate($schedule->getScheduledAt()));

        return ($scheduleAt > $limit);
    }
}
