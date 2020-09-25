<?php

namespace Phpro\Scheduler\Block\Adminhtml\Schedule\Add;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\Form as DataForm;
use Phpro\Scheduler\Model\ResourceModel\Schedule\Grid\JobCode\Options;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;

/**
 * @psalm-suppress DeprecatedClass
 */
class Form extends Generic
{
    /**
     * @var Options
     */
    private $jobCodeOptions;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Options $jobCodeOptions,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->jobCodeOptions = $jobCodeOptions;
    }

    protected function _construct()
    {
        parent::_construct();

        $this->setId('add_job_schedule');
    }

    protected function _prepareForm()
    {
        /** @var DataForm $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post'
                ]
            ]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Job Settings')]);

        $fieldset->addField(
            'job_code',
            'select',
            [
                'name' => 'job_code',
                'label' => __('Job Code'),
                'class' => 'required-entry',
                'required' => true,
                'values' => $this->jobCodeOptions->toOptionArray()
            ]
        );

        $form->setAction($this->getUrl('scheduler/schedule/save'));
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
