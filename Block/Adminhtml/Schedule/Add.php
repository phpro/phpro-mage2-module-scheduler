<?php
namespace Phpro\Scheduler\Block\Adminhtml\Schedule;

use Magento\Backend\Block\Widget\Form\Container;

/**
 * Class Add
 * @package Phpro\Scheduler\Block\Adminhtml\Schedule
 */
class Add extends Container
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_objectId = 'schedule_id';
        $this->_controller = 'adminhtml_schedule';
        $this->_blockGroup = 'Phpro_Scheduler';
        $this->_mode = 'add';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Schedule Job'));
    }

    /**
     * @inheritdoc
     */
    public function getHeaderText()
    {
        return __('Add New Job To Schedule');
    }
}
