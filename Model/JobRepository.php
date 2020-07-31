<?php

namespace Phpro\Scheduler\Model;

use Magento\Cron\Model\Config;
use Magento\Framework\Exception\NoSuchEntityException;
use Phpro\Scheduler\Model\ResourceModel\DisabledJob as DisabledJobResource;

/**
 * Class JobRepository
 * @package Phpro\Scheduler\Model
 */
class JobRepository
{
    /**
     * @var Config
     */
    private $cronConfig;

    /**
     * @var DisabledJobFactory
     */
    private $disabledJobFactory;

    /**
     * @var DisabledJobResource
     */
    private $disabledJobResource;

    /**
     * @var ScheduleManager
     */
    private $scheduleManager;

    /**
     * JobRepository constructor.
     * @param Config $cronConfig
     * @param DisabledJobFactory $disabledJobFactory
     * @param DisabledJobResource $disabledJobResource
     * @param ScheduleManager $scheduleManager
     */
    public function __construct(
        Config $cronConfig,
        DisabledJobFactory $disabledJobFactory,
        DisabledJobResource $disabledJobResource,
        ScheduleManager $scheduleManager
    ) {
        $this->cronConfig = $cronConfig;
        $this->disabledJobFactory = $disabledJobFactory;
        $this->disabledJobResource = $disabledJobResource;
        $this->scheduleManager = $scheduleManager;
    }

    /**
     * @return Job[]
     */
    public function getList()
    {
        $groups = $this->cronConfig->getJobs();
        $jobs = [];

        foreach ($groups as $group => $jobsConfiguration) {
            foreach ($jobsConfiguration as $jobConfiguration) {
                $name = isset($jobConfiguration['name']) ? $jobConfiguration['name'] : '';
                $instance = isset($jobConfiguration['instance']) ? $jobConfiguration['instance'] : '';
                $method = isset($jobConfiguration['method']) ? $jobConfiguration['method'] : '';
                $schedule = isset($jobConfiguration['schedule']) ? $jobConfiguration['schedule'] : '';
                $status = Job::STATUS_ENABLED;

                if (isset($jobConfiguration['name'])) {
                    $isEnabled = $this->isEnabled($jobConfiguration['name']);
                    $status = $isEnabled ? Job::STATUS_ENABLED : Job::STATUS_DISABLED;
                }

                $job = new Job();

                $job->setName($name);
                $job->setInstance($instance);
                $job->setMethod($method);
                $job->setSchedule($schedule);
                $job->setStatus($status);

                $jobs[$job->getName()]= $job;
            }
        }

        ksort($jobs);

        return $jobs;
    }

    /**
     * @param string $jobCode
     */
    public function disable(string $jobCode)
    {
        /** @var DisabledJob $disableJob */
        $disableJob = $this->disabledJobFactory->create();

        $disableJob->setJobCode($jobCode);

        $this->disabledJobResource->save($disableJob);
        $this->scheduleManager->removeScheduledTasksForJob($jobCode);
    }

    /**
     * @param string $jobCode
     */
    public function enable(string $jobCode)
    {
        try {
            $disabledJob = $this->getDisabledJob($jobCode);
        } catch (NoSuchEntityException $exception) {
            return;
        }

        $this->disabledJobResource->delete($disabledJob);
    }

    /**
     * @param string $jobCode
     * @return bool
     */
    public function isEnabled(string $jobCode)
    {
        try {
            $this->getDisabledJob($jobCode);
        } catch (NoSuchEntityException $exception) {
            return true;
        }

        return false;
    }

    /**
     * @param string $jobCode
     * @return DisabledJob
     * @throws NoSuchEntityException
     */
    private function getDisabledJob(string $jobCode)
    {
        /** @var DisabledJob $disableJob */
        $disableJob = $this->disabledJobFactory->create();

        $this->disabledJobResource->load($disableJob, $jobCode);

        if ($disableJob->getJobCode()) {
            return $disableJob;
        }

        throw new NoSuchEntityException();
    }
}
