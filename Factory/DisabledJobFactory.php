<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Factory;

use Magento\Framework\ObjectManagerInterface;
use Phpro\Scheduler\Model\DisabledJob;

class DisabledJobFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create(array $data = []): DisabledJob
    {
        return $this->objectManager->create(DisabledJob::class, $data);
    }
}
