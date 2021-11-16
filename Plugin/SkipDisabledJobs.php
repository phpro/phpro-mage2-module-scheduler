<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Plugin;

use Phpro\Scheduler\Model\JobRepository;
use Magento\Cron\Model\Schedule;

class SkipDisabledJobs
{
    /**
     * @var JobRepository
     */
    private $jobRepository;

    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function afterTrySchedule(Schedule $subject, bool $result): bool
    {
        if ($subject->getScheduledManually()) {
            return true;
        }

        if (!$this->jobRepository->isEnabled($subject->getJobCode())) {
            return false;
        }

        return $result;
    }
}
