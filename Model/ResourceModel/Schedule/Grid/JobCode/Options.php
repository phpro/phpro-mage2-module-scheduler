<?php
namespace Phpro\Scheduler\Model\ResourceModel\Schedule\Grid\JobCode;

use Magento\Framework\Data\OptionSourceInterface;
use Phpro\Scheduler\Model\JobRepository;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    private $jobs;

    public function __construct(JobRepository $jobRepository)
    {
        $this->jobs = [];

        foreach ($jobRepository->getList() as $job) {
            $this->jobs[] = $job->getName();
        }

        asort($this->jobs);
    }

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
