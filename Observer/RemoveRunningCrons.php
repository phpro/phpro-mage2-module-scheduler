<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Observer;

use Magento\Cron\Model\ResourceModel\Schedule\Collection;
use Magento\Cron\Model\Schedule;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Phpro\Scheduler\Config\CronConfiguration;
use Phpro\Scheduler\Factory\Schedule\CollectionFactory;
use Phpro\Scheduler\Model\ScheduleManager;

class RemoveRunningCrons implements ObserverInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Collection|null
     */
    private $runningCrons = null;

    /**
     * @var int|null
     */
    private $jobLifeTimeInSeconds;

    /**
     * @var CronConfiguration
     */
    private $config;

    /**
     * @var ScheduleManager
     */
    private $scheduleManager;

    public function __construct(
        CronConfiguration $config,
        CollectionFactory $collectionFactory,
        ScheduleManager $scheduleManager
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->config = $config;
        $this->scheduleManager = $scheduleManager;
    }

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
                $this->scheduleManager->removeSchedule($schedule);
            }
        }
    }

    private function getRunningCrons(): Collection
    {
        if (null === $this->runningCrons) {
            $this->runningCrons = $this->collectionFactory->create()
                ->addFieldToFilter('status', Schedule::STATUS_RUNNING);
        }

        return $this->runningCrons;
    }

    private function getJobLifeTimeInSeconds(): int
    {
        if (!$this->jobLifeTimeInSeconds) {
            $this->jobLifeTimeInSeconds = $this->config->getRunningLifetime() * 60;
        }
        return $this->jobLifeTimeInSeconds;
    }
}
