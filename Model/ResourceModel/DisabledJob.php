<?php

namespace Phpro\Scheduler\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Supresses errors from core class(es)
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class DisabledJob extends AbstractDb
{
    /**
     * @var bool
     */
    protected $_isPkAutoIncrement = false;

    protected function _construct()
    {
        $this->_init('disabled_crons', 'job_code');
    }
}
