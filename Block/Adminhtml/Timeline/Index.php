<?php

namespace Phpro\Scheduler\Block\Adminhtml\Timeline;

use Magento\Backend\Block\Template;
use Magento\Cron\Model\Schedule;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Cron\Model\ResourceModel\Schedule\Collection;
use Magento\Backend\Block\Widget\Context;
use Phpro\Scheduler\Config\CronConfiguration;
use Phpro\Scheduler\Config\Source\TimelineLimit;
use Phpro\Scheduler\Data\Schedule as ScheduleData;
use Phpro\Scheduler\Service\CronDataService;
use Phpro\Scheduler\Util\DateTimeConverter;

/**
 * Class Index
 * @package Phpro\Scheduler\Block\Adminhtml\Timeline
 */
class Index extends Template
{
    /**
     * @var array
     */
    protected $cronData;

    /**
     * @var int
     */
    private $startTime;

    /**
     * @var int
     */
    private $endTime;

    /**
     * Amount of seconds per pixel
     *
     * @var int
     */
    private $zoom = 15;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var DateTimeConverter
     */
    private $converter;

    /**
     * @var CronConfiguration
     */
    private $config;

    /**
     * @var CronDataService
     */
    private $cronDataService;

    public function __construct(
        Context $context,
        DateTime $dateTime,
        DateTimeConverter $converter,
        CronConfiguration $config,
        CronDataService $cronDataService,
        array $data = []
    ) {
        $this->dateTime = $dateTime;
        $this->cronData = [];
        $this->converter = $converter;
        $this->config = $config;
        $this->cronDataService = $cronDataService;

        parent::__construct($context, $data);
    }

    private function initializeStartAndEndTime(): void
    {
        $minDate = null;
        $maxDate = null;

        /** @var ScheduleData $schedule */
        foreach ($this->cronDataService->getCronData() as $schedule) {
            $startTime = $schedule->getScheduledAt();
            $minDate = (null === $minDate) ? $startTime : min($minDate, $startTime);
            $maxDate = (null === $maxDate) ? $startTime : max($maxDate, $startTime);

            $this->cronData[$schedule->getJobCode()][] = $schedule;
        }

        $this->startTime = $this->configureStartTime($minDate);

        if ($maxDate !== null) {
            $this->endTime = $this->converter->toNextHourTimestamp($maxDate);
        }
    }

    private function configureStartTime($date): int
    {
        if ($this->config->getTimelineLimit() === TimelineLimit::LIMIT_0) {
            return $this->converter->toHourTimestamp(null === $date ? 'now' : $date);
        }

        return ($this->converter->toHourTimestamp('now') - ($this->config->getTimelineLimit() * 3600));
    }

    public function getAvailableJobCodes(): array
    {
        if (empty($this->cronData)) {
            $this->initializeStartAndEndTime();
        }

        $codes = array_keys($this->cronData);
        sort($codes);

        return $codes;
    }

    public function getSchedulesForCode(string $jobCode): array
    {
        return $this->cronData[$jobCode];
    }

    public function getRunningOffset(ScheduleData $schedule): float
    {
        if ($this->isCurrentlyRunning($schedule)) {
            $duration = $this->getRunningDuration($schedule);
            $offset = $this->getOffset($schedule);
            $offset += $duration;

            return $offset;
        }

        return $this->getOffset($schedule);
    }

    public function isCurrentlyRunning(ScheduleData $schedule): bool
    {
        if ($schedule->getStatus() == Schedule::STATUS_RUNNING) {
            return true;
        }

        return false;
    }

    public function getRunningDuration(ScheduleData $schedule): float
    {
        if ($this->isCurrentlyRunning($schedule)) {
            $duration = strtotime(360) - time();
            $duration = $duration / $this->zoom;

            return $duration;
        }

        return $this->getDuration($schedule);
    }

    public function getDuration(ScheduleData $schedule): float
    {
        $duration = $schedule->getDuration() ?: 0;

        if ($schedule->getStatus() == Schedule::STATUS_RUNNING) {
            $duration = $this->converter->toCurrentTimestamp()
                - $this->converter->toTimestamp($schedule->getExecutedAt());
        }

        $duration = $duration / $this->zoom;
        $duration = ceil($duration / 4) * 4 - 1; // round to numbers dividable by 4, then remove 1 px border
        $duration = max($duration, 3);

        return $duration;
    }

    public function getOffset(ScheduleData $schedule): float
    {
        $offset = ($this->converter->toTimestamp($schedule->getScheduledAt()) - $this->startTime) / $this->zoom;

        // We need to measure the offset by the execution time rather then the scheduled_at time.
        if ($schedule->getExecutedAt() !== '') {
            $offset = ($this->converter->toTimestamp($schedule->getExecutedAt()) - $this->startTime) / $this->zoom;
        }

        if ($offset < 0) { // cut bar
            $offset = 0;
        }

        return $offset;
    }

    public function getTimelinePanelWidth(): float
    {
        return ($this->endTime - $this->startTime) / $this->zoom;
    }

    public function getNowLine(): float
    {
        return ($this->converter->toCurrentTimestamp() - $this->startTime) / $this->zoom;
    }

    public function getHours(): array
    {
        $hours = [];
        $hour = $this->getStartTime();

        do {
            $hours[] = $this->decorateTime($hour, false, 'Y-m-d H:i');
            $hour += 3600;
        } while ($hour <= $this->getEndTime());

        return $hours;
    }

    public function decorateTime(string $value, bool $echoToday = false, ?string $dateFormat = null): string
    {
        if (empty($value) || $value == '0000-00-00 00:00:00') {
            $value = '';
        } else {
            $value = $this->dateTime->date($dateFormat, $value);
            $replace = [
                $this->dateTime->date('Y-m-d ', time()) => $echoToday ? __('Today') . ' ' : '',
                $this->dateTime->date('Y-m-d ', strtotime('+1 day')) => __('Tomorrow') . ' ',
                $this->dateTime->date('Y-m-d ', strtotime('-1 day')) => __('Yesterday') . ' '
            ];
            $value = str_replace(array_keys($replace), array_values($replace), $value);
        }

        return $value;
    }

    public function getStartTime(): int
    {
        return $this->startTime;
    }

    public function getEndTime(): int
    {
        return $this->endTime;
    }
}
