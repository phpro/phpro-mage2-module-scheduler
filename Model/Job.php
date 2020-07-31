<?php

namespace Phpro\Scheduler\Model;

use Magento\Framework\DataObject;

/**
 * Class Job
 * @package Phpro\Scheduler\Model
 *
 * @method setName(string $name)
 * @method string getName()
 * @method setInstance(string $instance)
 * @method string getInstance()
 * @method setMethod(string $method)
 * @method string getMethod()
 * @method setSchedule(string $schedule)
 * @method string getSchedule()
 * @method int getStatus()
 * @method string getStatusName()
 */
class Job extends DataObject
{
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status)
    {
        $this->setData('status', $status);
        // TODO: Use getStatusName() to to get the correct status instead of a setter. The column in
        // scheduler_jobconfiguration_index.xml should have its own renderer that uses getStatusName() instead
        // of getData()
        $this->setStatusName();
        return $this;
    }

    private function setStatusName()
    {
        if ($this->getStatus() === self::STATUS_ENABLED) {
            $this->setData('status_name', __('Enabled'));
            return;
        }

        $this->setData('status_name', __('Disabled'));
    }
}
