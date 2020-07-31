<?php

namespace Phpro\Scheduler\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class DisabledJob
 * @package Phpro\Scheduler\Model\ResourceModel
 */
class DisabledJob extends AbstractDb
{
    /**
     * @var bool
     */
    protected $_isPkAutoIncrement = false;

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('disabled_crons', 'job_code');
    }
}
