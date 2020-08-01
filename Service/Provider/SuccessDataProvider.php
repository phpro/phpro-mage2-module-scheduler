<?php
declare(strict_types=1);

namespace Phpro\Scheduler\Service\Provider;

use Magento\Cron\Model\ResourceModel\Schedule\CollectionFactory;
use Magento\Cron\Model\Schedule;
use Magento\Framework\Api\SortOrder;
use Phpro\Scheduler\Api\Service\ProviderInterface;
use Phpro\Scheduler\Config\CronConfiguration;
use Phpro\Scheduler\Service\TimeLineValidator;

class SuccessDataProvider implements ProviderInterface
{
    private const SUCCESS_LIMIT = 50;

    /**
     * @var CollectionFactory
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
        CollectionFactory $collectionFactory,
        CronConfiguration $config,
        TimeLineValidator $validator
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->config = $config;
        $this->validator = $validator;
    }

    public function provide(): array
    {
        $collection = $this->collectionFactory->create()
            ->resetData()
            ->addFieldToFilter('status', ['eq' => Schedule::STATUS_SUCCESS])
            ->setOrder('executed_at', SortOrder::SORT_DESC);

        if ($this->config->limitSuccessfulJobs()) {
            $collection = $collection
                ->setPageSize(self::SUCCESS_LIMIT)
                ->setCurPage(1);
        }
        
        $validator = $this->validator;
        return array_filter(iterator_to_array($collection), static function ($schedule) use ($validator) {
            return $validator->validate($schedule);
        });
    }
}
