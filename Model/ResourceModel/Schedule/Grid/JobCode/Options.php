<?php
namespace Phpro\Scheduler\Model\ResourceModel\Schedule\Grid\JobCode;

use Magento\Framework\Data\OptionSourceInterface;
use Phpro\Scheduler\Model\JobRepository;

/**
 * Class Options
 * @package Phpro\Scheduler\Model\ResourceModel\Schedule\Grid\JobCode
 */
class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    private $jobs;

    /**
     * Options constructor.
     * @param JobRepository $jobRepository
     */
    public function __construct(JobRepository $jobRepository)
    {
        $this->jobs = [];

        foreach ($jobRepository->getList() as $job) {
            $this->jobs[] = $job->getName();
        }

        asort($this->jobs);
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $options = [];

        foreach ($this->jobs as $job) {
            $options[] = [
                'label' => $job,
                'value' => $job
            ];
        }

        return $options;
    }
}
