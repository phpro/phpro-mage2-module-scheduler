<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Test\Model;

use Magento\Cron\Model\Schedule;
use Magento\Cron\Model\ResourceModel\Schedule as ScheduleResource;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Phpro\Scheduler\Factory\Schedule\CronCollectionFactory;
use Phpro\Scheduler\Model\ScheduleManager;
use Phpro\Scheduler\Factory\Schedule\ScheduleFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ScheduleManagerTest extends TestCase
{
    /**
     * @var MockObject|ScheduleFactory
     */
    private $scheduleFactory;

    /**
     * @var MockObject|CronCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var MockObject|Schedule
     */
    private $schedule;

    /**
     * @var MockObject|ScheduleResource
     */
    private $scheduleResource;

    /**
     * @var MockObject\ScheduleManager
     */
    private $scheduleManager;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp(): void
    {
        $this->scheduleFactory = $this->createMock(ScheduleFactory::class);
        $this->schedule = $this->getMockBuilder(Schedule::class)
            ->disableOriginalConstructor()
            ->addMethods(['setJobCode', 'setCreatedAt', 'setScheduledAt'])
            ->getMock();

        $this->scheduleResource = $this->getMockBuilder(ScheduleResource::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionFactory = $this->createMock(CronCollectionFactory::class);

        $this->scheduleManager =  new ScheduleManager(
            $this->scheduleFactory,
            $this->collectionFactory,
            $this->scheduleResource
        );

        $this->objectManager = new ObjectManager($this);
    }

    public function testItCanAddJobToSchedule(): void
    {
        $jobCode = 'test';

        $this->scheduleFactory
            ->expects(static::once())
            ->method('create')
            ->willReturn($this->schedule);

        $this->schedule
            ->expects(static::once())
            ->method('setJobCode')
            ->with($jobCode);

        $this->scheduleResource
            ->expects(static::once())
            ->method('save')
            ->with($this->schedule);

        $this->scheduleManager->addJobToSchedule($jobCode);
    }

    public function testItCanRemoveSchedule(): void
    {
        $schedule = $this->createMock(Schedule::class);

        $this->scheduleResource
            ->expects(static::once())
            ->method('delete')
            ->with($schedule);

        $this->scheduleManager->removeSchedule($schedule);
    }

    public function testItRemovesSchedulesByJob(): void
    {
        $jobCode = 'wilma_flinstone';
        $collection = $this->objectManager->getCollectionMock(ScheduleResource\Collection::class, [
            $schedule = $this->createMock(Schedule::class),
        ]);

        $this->collectionFactory
            ->expects(static::once())
            ->method('create')
            ->willReturn($collection);

        $collection
            ->method('addFieldToFilter')
            ->withConsecutive(['job_code',$jobCode],['status','pending']);

        $this->scheduleResource->expects(static::once())
            ->method('delete')
            ->with($schedule);

        $this->scheduleManager->removeScheduledTasksForJob($jobCode);
    }
}
