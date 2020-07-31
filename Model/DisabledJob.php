<?php

namespace Phpro\Scheduler\Model;

use Magento\Framework\Model\AbstractModel;
use Phpro\Scheduler\Model\ResourceModel\DisabledJob as DisabledJobResource;

/**
 * Class DisabledJob
 * @package Phpro\Scheduler\Model
 *
 * TODO: Rework this to a resource model only. We only need a few basic queries not a model.
 *
 * @method setJobCode(string $jobCode)
 * @method string getJobCode()
 * @method setCreatedAt(string $createdAt)
 * @method string getCreatedAt()
 */
class DisabledJob extends AbstractModel
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(DisabledJobResource::class);
    }
}
