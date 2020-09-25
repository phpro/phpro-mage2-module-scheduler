<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Factory\Schedule;

use Magento\Cron\Model\ResourceModel\Schedule\Collection;
use Magento\Framework\ObjectManagerInterface;

class CollectionFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create(array $data = []): Collection
    {
        return $this->objectManager->create(
            Collection::class,
            $data
        );
    }
}
