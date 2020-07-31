<?php
namespace Phpro\Scheduler\Test\Model;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Cron\Model\ScheduleFactory;
use Magento\Cron\Model\Schedule;
use Magento\Cron\Model\ResourceModel\Schedule as ScheduleResource;
use Magento\Cron\Model\ResourceModel\Schedule\CollectionFactory;
use Phpro\Scheduler\Model\ScheduleManager;
use PHPUnit\Framework\TestCase;

/**
 * Class ScheduleManagerTest
 * @package Phpro\Scheduler\Test\Model
 */
class ScheduleManagerTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|DateTime
     */
    private $dateTime;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ScheduleFactory
     */
    private $scheduleFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Schedule
     */
    private $schedule;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ScheduleResource
     */
    private $scheduleResource;

    protected function setUp()
    {
        $this->dateTime = $this->getMockBuilder(DateTime::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scheduleFactory = $this->getMockBuilder('Magento\Cron\Model\ScheduleFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->schedule = $this->getMockBuilder(Schedule::class)
            ->disableOriginalConstructor()
            ->setMethods(['setJobCode', 'setCreatedAt', 'setScheduledAt', 'getResource'])
            ->getMock();

        $this->scheduleResource = $this->getMockBuilder(ScheduleResource::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionFactory = $this->getMockBuilder('Magento\Cron\Model\ResourceModel\Schedule\CollectionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
    }

    /**
     * @test
     */
    public function itCanAddJobToSchedule()
    {
        $jobCode = 'test';

        $this->dateTime->expects($this->once())
            ->method('gmtTimestamp');

        $this->schedule->expects($this->once())
            ->method('setJobCode')
            ->with($jobCode);

        $this->schedule->expects($this->once())
            ->method('setCreatedAt');

        $this->schedule->expects($this->once())
            ->method('setScheduledAt');

        $this->scheduleResource->expects($this->once())
            ->method('save')
            ->with($this->schedule);

        $this->schedule->expects($this->once())
            ->method('getResource')
            ->willReturn($this->scheduleResource);

        $this->scheduleFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->schedule);

        $scheduleManger = new ScheduleManager($this->dateTime, $this->scheduleFactory, $this->collectionFactory);
        $scheduleManger->addJobToSchedule($jobCode);
    }
}
