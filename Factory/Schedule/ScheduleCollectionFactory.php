<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Factory\Schedule;

use Magento\Framework\ObjectManagerInterface;
use Phpro\Scheduler\Model\ResourceModel\Schedule\ScheduleCollection;

class ScheduleCollectionFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create(array $data = []): ScheduleCollection
    {
        return $this->objectManager->create(ScheduleCollection::class, $data);
    }
}
