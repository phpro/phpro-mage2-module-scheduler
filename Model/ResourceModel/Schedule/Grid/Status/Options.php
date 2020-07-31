<?php
namespace Phpro\Scheduler\Model\ResourceModel\Schedule\Grid\Status;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Cron\Model\Schedule;

/**
 * Class Options
 * @package Phpro\Scheduler\Model\ResourceModel\Schedule\Grid\Status
 */
class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    private $statuses;

    /**
     * Options constructor.
     */
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

    /**
     * @inheritdoc
     */
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
