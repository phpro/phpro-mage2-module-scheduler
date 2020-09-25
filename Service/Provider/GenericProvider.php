<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Service\Provider;

use Magento\Cron\Model\Schedule;
use Magento\Framework\Api\SortOrder;
use Phpro\Scheduler\Api\Service\ProviderInterface;
use Phpro\Scheduler\Factory\Schedule\CollectionFactory;
use Phpro\Scheduler\Service\TimeLineValidator;

class GenericProvider implements ProviderInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var TimeLineValidator
     */
    private $validator;

    /**
     * @var array
     */
    private $statusFilter;

    public function __construct(
        CollectionFactory $collectionFactory,
        TimeLineValidator $validator,
        array $statusFilter
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->validator = $validator;
        $this->statusFilter = $statusFilter;
    }

    public function provideCronData(): array
    {
        $collection = $this->collectionFactory
            ->create()
            ->addFieldToFilter('status', ['in' => $this->statusFilter]);
        $validator = $this->validator;

        return array_filter(iterator_to_array($collection), static function ($schedule) use ($validator) {
            return $validator->validate($schedule);
        });
    }
}
