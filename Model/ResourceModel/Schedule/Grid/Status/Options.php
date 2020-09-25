<?php
namespace Phpro\Scheduler\Model\ResourceModel\Schedule\Grid\Status;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Cron\Model\Schedule;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    private $statuses;

    public function __construct()
    {
        $this->statuses = [
            Schedule::STATUS_PENDING,
            Schedule::STATUS_RUNNING,
            Schedule::STATUS_SUCCESS,
            Schedule::STATUS_MISSED,
            Schedule::STATUS_ERROR
        ];
    }

    public function toOptionArray()
    {
        $options = [];

        foreach ($this->statuses as $status) {
            $options[] = [
                'label' => $status,
                'value' => $status
            ];
        }

        return $options;
    }
}
