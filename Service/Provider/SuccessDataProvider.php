<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Service\Provider;

use Magento\Cron\Model\Schedule;
use Magento\Framework\Api\SortOrder;
use Phpro\Scheduler\Api\Service\ProviderInterface;
use Phpro\Scheduler\Config\CronConfiguration;
use Phpro\Scheduler\Factory\Schedule\CronCollectionFactory;
use Phpro\Scheduler\Service\TimeLineValidator;

class SuccessDataProvider implements ProviderInterface
{
    private const SUCCESS_LIMIT = 50;

    /**
     * @var CronCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CronConfiguration
     */
    private $config;

    /**
     * @var TimeLineValidator
     */
    private $validator;

    public function __construct(
        CronCollectionFactory $collectionFactory,
        CronConfiguration $config,
        TimeLineValidator $validator
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->config = $config;
        $this->validator = $validator;
    }

    public function provideCronData(): array
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('status', ['eq' => Schedule::STATUS_SUCCESS])
            ->setOrder('executed_at', SortOrder::SORT_DESC);

        if ($this->config->limitSuccessfulJobs()) {
            $collection = $collection
                ->setPageSize(self::SUCCESS_LIMIT)
                ->setCurPage(1);
        }

        $validator = $this->validator;
        return array_filter(iterator_to_array($collection), static function (Schedule $schedule) use ($validator) {
            return $validator->validate($schedule);
        });
    }
}
