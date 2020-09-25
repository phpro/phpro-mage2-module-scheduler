<?php

declare(strict_types=0);

namespace Phpro\Scheduler\Block\Adminhtml\JobConfiguration;

use Magento\Backend\Block\Widget\Grid\Container;

class Grid extends Container
{
    protected function _construct()
    {
        $this->_controller = "jobconfiguration";
        $this->_blockGroup = "phpro_scheduler";
        $this->_headerText = __('Job Configuration');

        parent::_construct();
        $this->removeButton('add');
    }
}
