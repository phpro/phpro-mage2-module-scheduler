<?php

namespace Phpro\Scheduler\Plugin;

use Phpro\Scheduler\Model\JobRepository;
use Magento\Cron\Model\Schedule;

/**
 * Class SkipDisabledJobs
 * @package Phpro\Scheduler\Plugin
 */
class SkipDisabledJobs
{
    /**
     * @var JobRepository
     */
    private $jobRepository;

    /**
     * SkipDisabledJobs constructor.
     * @param JobRepository $jobRepository
     */
    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    /**
     * @param Schedule $subject
     * @param bool $result
     * @return bool
     */
    public function afterTrySchedule(Schedule $subject, $result)
    {
        if (!$this->jobRepository->isEnabled($subject->getJobCode())) {
            return false;
        }

        return $result;
    }
}
