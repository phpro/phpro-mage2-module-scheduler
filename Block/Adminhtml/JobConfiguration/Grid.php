<?php

namespace Phpro\Scheduler\Block\Adminhtml\JobConfiguration;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Class Grid
 * @package Phpro\Scheduler\Block\Adminhtml\JobConfiguration
 */
class Grid extends Container
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_controller = "jobconfiguration";
        $this->_blockGroup = "phpro_scheduler";
        $this->_headerText = __('Job Configuration');

        parent::_construct();
        $this->removeButton('add');
    }
}
