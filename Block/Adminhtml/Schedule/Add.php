<?php
namespace Phpro\Scheduler\Block\Adminhtml\Schedule;

use Magento\Backend\Block\Widget\Form\Container;

class Add extends Container
{
    protected function _construct()
    {
        $this->_objectId = 'schedule_id';
        $this->_controller = 'adminhtml_schedule';
        $this->_blockGroup = 'Phpro_Scheduler';
        $this->_mode = 'add';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Schedule Job'));
    }

    public function getHeaderText()
    {
        return __('Add New Job To Schedule');
    }
}
