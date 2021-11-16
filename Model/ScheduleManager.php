<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Model;

use Magento\Cron\Model\Schedule;
use Magento\Cron\Model\ResourceModel\Schedule as ResourceModel;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Phpro\Scheduler\Factory\Schedule\CronCollectionFactory;
use Phpro\Scheduler\Factory\Schedule\ScheduleFactory;

class ScheduleManager
{
    /**
     * @var ScheduleFactory
     */
    private $scheduleFactory;

    /**
     * @var CronCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var ResourceModel
     */
    private $resourceModel;

    public function __construct(
        ScheduleFactory $scheduleFactory,
        CronCollectionFactory $collectionFactory,
        ResourceModel $resourceModel
    ) {
        $this->scheduleFactory = $scheduleFactory;
        $this->collectionFactory = $collectionFactory;
        $this->resourceModel = $resourceModel;
    }

    public function addJobToSchedule(string $jobCode): void
    {
        $schedule = $this->scheduleFactory->create();
        $schedule->setJobCode($jobCode);
        $schedule->setData('scheduled_manually', true);
        $this->resourceModel->save($schedule);
    }

    public function removeSchedule(Schedule $schedule): void
    {
        $this->resourceModel->delete($schedule);
    }

    public function removeScheduledTasksForJob(string $jobCode): void
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('job_code', $jobCode);
        $collection->addFieldToFilter('status', 'pending');

        foreach ($collection as $schedule) {
            $this->removeSchedule($schedule);
        }
    }
}
