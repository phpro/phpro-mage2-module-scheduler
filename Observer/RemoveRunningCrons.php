<?php

namespace Phpro\Scheduler\Observer;

use Magento\Cron\Model\ResourceModel\Schedule\Collection;
use Magento\Cron\Model\Schedule;
use Magento\Cron\Model\ScheduleFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Phpro\Scheduler\Config\CronConfiguration;

/**
 * Class RemoveRunningCrons
 * @package Phpro\Scheduler\Observer
 */
class RemoveRunningCrons implements ObserverInterface
{
    /**
     * @var ScheduleFactory
     */
    private $scheduleFactory;

    /**
     * @var Collection
     */
    private $runningCrons;

    /**
     * @var float|null
     */
    private $jobLifeTimeInSeconds;

    /**
     * @var CronConfiguration
     */
    private $config;

    public function __construct(CronConfiguration $config, ScheduleFactory $scheduleFactory)
    {
        $this->scheduleFactory = $scheduleFactory;
        $this->config = $config;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $jobLifeTimeInSeconds = $this->getJobLifeTimeInSeconds();
        if (!$jobLifeTimeInSeconds) {
            return;
        }

        $runningCrons = $this->getRunningCrons();
        /** @var \Magento\Cron\Model\Schedule $schedule */
        foreach ($runningCrons as $schedule) {
            $now = strtotime(date("Y-m-d H:i:s"));
            $scheduledAt = strtotime($schedule->getData('scheduled_at'));
            $timeDiffInSeconds = $now - $scheduledAt;

            if ($timeDiffInSeconds > $jobLifeTimeInSeconds) {
                $schedule->delete();
            }
        }
    }

    /**
     * @return Collection
     */
    private function getRunningCrons()
    {
        if (!$this->runningCrons) {
            $this->runningCrons = $this->scheduleFactory->create()
                ->getCollection()
                ->addFieldToFilter('status', Schedule::STATUS_RUNNING)
                ->load();
        }
        return $this->runningCrons;
    }

    /**
     * @return int
     */
    private function getJobLifeTimeInSeconds()
    {
        if (!$this->jobLifeTimeInSeconds) {
            $this->jobLifeTimeInSeconds = (int)$this->config->getRunningLifetime() * 60;
        }
        return $this->jobLifeTimeInSeconds;
    }
}
