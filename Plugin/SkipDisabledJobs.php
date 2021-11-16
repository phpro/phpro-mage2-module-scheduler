<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Plugin;

use Phpro\Scheduler\Model\JobRepository;
use Magento\Cron\Model\Schedule;
use Magento\Cron\Model\ScheduleFactory;

class SkipDisabledJobs
{
    private JobRepository $jobRepository;
    private ScheduleFactory $scheduleFactory;

    public function __construct(
        JobRepository $jobRepository,
        ScheduleFactory $scheduleFactory
    ) {
        $this->jobRepository = $jobRepository;
        $this->scheduleFactory = $scheduleFactory;
    }

    public function afterTrySchedule(Schedule $subject, bool $result): bool
    {
        if (!$this->jobRepository->isEnabled($subject->getJobCode())) {
            return false;
        }

        // check if we find a schedule with the same scheduled_at and the scheduled_manually active
        // if so, we return true
        $pendingJobs = $this->scheduleFactory->create()->getCollection();
        $pendingJobs->addFieldToFilter('status', Schedule::STATUS_PENDING);
        $pendingJobs->addFieldToFilter('job_code', ['eq' => $subject->getJobCode()]);
        $pendingJobs->addFieldToFilter('scheduled_manually', 1);

        if ($pendingJobs->count()) {
            return true;
        }

        return $result;
    }
}
