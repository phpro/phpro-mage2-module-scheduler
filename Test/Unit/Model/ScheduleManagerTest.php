<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Test\Model;

use Magento\Cron\Model\Schedule;
use Magento\Cron\Model\ResourceModel\Schedule as ScheduleResource;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Phpro\Scheduler\Factory\Schedule\CollectionFactory;
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
     * @var MockObject|CollectionFactory
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
    private $scheduleMananger;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp(): void
    {
        $this->scheduleFactory = $this->createMock(ScheduleFactory::class);
        $this->schedule = $this->getMockBuilder(Schedule::class)
            ->disableOriginalConstructor()
            ->setMethods(['setJobCode', 'setCreatedAt', 'setScheduledAt', 'getResource'])
            ->getMock();

        $this->scheduleResource = $this->getMockBuilder(ScheduleResource::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionFactory = $this->createMock(CollectionFactory::class);

        $this->scheduleMananger =  new ScheduleManager(
            $this->scheduleFactory,
            $this->collectionFactory,
            $this->scheduleResource
        );

        $this->objectManager = new ObjectManager($this);
    }

    public function testItCanAddJobToSchedule(): void
    {
        $jobCode = 'test';

        $schedule = $this->createMock(Schedule::class);

        $this->scheduleFactory
            ->expects(static::once())
            ->method('create')
            ->willReturn($schedule);

        $schedule
            ->expects(static::once())
            ->method('setJobCode')
            ->with($jobCode);

        $this->scheduleResource
            ->expects(static::once())
            ->method('save')
            ->with($schedule);

        $this->scheduleManager->addJobToSchedule($jobCode);
    }

    public function testItCanRemoveSchedule(): void
    {
        $schedule = $this->createMock(Schedule::class);

        $this->scheduleResource
            ->expects(static::once())
            ->method('delete')
            ->with($schedule);

        $this->scheduleMananger->removeSchedule($schedule);
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
            ->expects(static::at(0))
            ->method('addFieldToFilter')
            ->with('job_code', $jobCode);

        $collection
            ->expects(static::at(1))
            ->method('addFieldToFilter')
            ->with('status', 'pending');

        $this->scheduleResource->expects(static::once())
            ->method('delete')
            ->with($schedule);

        $this->scheduleMananger->removeScheduledTasksForJob($jobCode);
    }
}
