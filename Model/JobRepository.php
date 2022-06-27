<?php

namespace Phpro\Scheduler\Model;

use Magento\Cron\Model\Config;
use Magento\Framework\Exception\NoSuchEntityException;
use Phpro\Scheduler\Factory\DisabledJobFactory;
use Phpro\Scheduler\Model\ResourceModel\DisabledJob as DisabledJobResource;

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

    public function getList(): array
    {
        $groups = $this->cronConfig->getJobs();
        $jobs = [];

        foreach ($groups as $group => $jobsConfiguration) {
            foreach ($jobsConfiguration as $jobConfiguration) {
                $name = $jobConfiguration['name'] ?? '';
                $instance = $jobConfiguration['instance'] ?? '';
                $method = $jobConfiguration['method'] ?? '';
                $schedule = $jobConfiguration['schedule'] ?? '';
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

    public function disable(string $jobCode): void
    {
        /** @var DisabledJob $disableJob */
        $disableJob = $this->disabledJobFactory->create();

        $disableJob->setJobCode($jobCode);

        $this->disabledJobResource->save($disableJob);
        $this->scheduleManager->removeScheduledTasksForJob($jobCode);
    }

    public function enable(string $jobCode): void
    {
        try {
            $disabledJob = $this->getDisabledJob($jobCode);
        } catch (NoSuchEntityException $exception) {
            return;
        }

        $this->disabledJobResource->delete($disabledJob);
    }

    public function isEnabled(string $jobCode): bool
    {
        try {
            $this->getDisabledJob($jobCode);
        } catch (NoSuchEntityException $exception) {
            return true;
        }

        return false;
    }

    private function getDisabledJob(string $jobCode): DisabledJob
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
