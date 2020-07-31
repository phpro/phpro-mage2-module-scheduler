<?php

namespace Phpro\Scheduler\Model\ResourceModel\Schedule;

use Magento\Cron\Model\ResourceModel\Schedule\Collection;

/**
 * Fix for core collection class which does not have any configuration for the id field.
 */
class ScheduleCollection extends Collection
{
    protected $_idFieldName = 'schedule_id';
}
