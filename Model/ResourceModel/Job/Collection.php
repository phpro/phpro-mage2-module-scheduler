<?php

namespace Phpro\Scheduler\Model\ResourceModel\Job;

use Magento\Framework\Api\AbstractServiceCollection;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Data\Collection\EntityFactory;
use Phpro\Scheduler\Model\JobRepository;

class Collection extends AbstractServiceCollection
{
    /**
     * @var JobRepository
     */
    private $jobRepository;

    public function __construct(
        EntityFactory $entityFactory,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        JobRepository $jobRepository
    ) {
        parent::__construct($entityFactory, $filterBuilder, $searchCriteriaBuilder, $sortOrderBuilder);

        $this->jobRepository = $jobRepository;
    }

    public function loadData($printQuery = false, $logQuery = false)
    {
        if (empty($this->_items)) {
            $this->_items = $this->jobRepository->getList();
        }
        return $this;
    }
}
