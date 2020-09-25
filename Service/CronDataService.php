<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Service;

use Magento\Cron\Model\Schedule;
use Phpro\Scheduler\Util\DateTimeConverter;
use Phpro\Scheduler\Data\Schedule as ScheduleData;

class CronDataService
{
    /**
     * @var array
     */
    private $providers;

    /**
     * @var DateTimeConverter
     */
    private $converter;

    public function __construct(array $providers, DateTimeConverter $converter)
    {
        $this->providers = $providers;
        $this->converter = $converter;
    }

    public function getCronData(): \Generator
    {
        foreach ($this->providers as $provider) {
            foreach ($provider->provideCronData() as $schedule) {
                if (false === ($schedule instanceof Schedule)) {
                    continue;
                }

                yield new ScheduleData(
                    $schedule->getStatus(),
                    (int)$schedule->getId(),
                    $schedule->getJobCode(),
                    $this->converter->convertDate($schedule->getCreatedAt()),
                    $this->converter->convertDate($schedule->getScheduledAt()),
                    $this->converter->convertDate($schedule->getExecutedAt()),
                    $this->converter->convertDate($schedule->getFinishedAt()),
                    $this->calculateExecutionTime($schedule),
                    $schedule->getMessages()
                );
            }
        }
    }

    private function calculateExecutionTime(Schedule $schedule): int
    {
        if ($schedule->getStatus() === Schedule::STATUS_ERROR) {
            return 0;
        }

        $to = new \DateTimeImmutable($schedule->getExecutedAt() ?: '');
        $from = new \DateTimeImmutable($schedule->getFinishedAt() ?: '');

        return $from->getTimestamp() - $to->getTimestamp();
    }
}
