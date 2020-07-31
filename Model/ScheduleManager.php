<?php
namespace Phpro\Scheduler\Model;

use Magento\Cron\Model\ScheduleFactory;
use Magento\Cron\Model\Schedule;
use Magento\Cron\Model\ResourceModel\Schedule\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class ScheduleManager
 * @package Phpro\Scheduler\Model
 */
class ScheduleManager
{
    const TIME_FORMAT = '%Y-%m-%d %H:%M:00';

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var ScheduleFactory
     */
    private $scheduleFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * ScheduleManager constructor.
     *
     * @param DateTime $dateTime
     * @param ScheduleFactory $scheduleFactory
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        DateTime $dateTime,
        ScheduleFactory $scheduleFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->dateTime = $dateTime;
        $this->scheduleFactory = $scheduleFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param string $jobCode
     * @throws \Exception
     */
    public function addJobToSchedule(string $jobCode)
    {
        $schedule = $this->createSchedule();

        $schedule->setJobCode($jobCode);
        $schedule->getResource()->save($schedule);
    }

    /**
     * @param string $jobCode
     */
    public function removeScheduledTasksForJob(string $jobCode)
    {
        $collection = $this->collectionFactory->create();

        $collection->addFieldToFilter('job_code', $jobCode);
        $collection->addFieldToFilter('status', 'pending');

        foreach ($collection as $schedule) {
            /** @var Schedule $schedule */
            $schedule->getResource()->delete($schedule);
        }
    }

    /**
     * @return Schedule
     */
    private function createSchedule()
    {
        /** @var Schedule $schedule */
        $schedule = $this->scheduleFactory->create();
        // Timestamp should always be stored in GMT
        $now = $this->dateTime->gmtTimestamp();

        $schedule->setCreatedAt(strftime(self::TIME_FORMAT, $now));
        $schedule->setScheduledAt(strftime(self::TIME_FORMAT, $now + 60));

        return $schedule;
    }
}
