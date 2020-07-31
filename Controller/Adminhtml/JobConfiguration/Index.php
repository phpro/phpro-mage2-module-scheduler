<?php

namespace Phpro\Scheduler\Controller\Adminhtml\JobConfiguration;

use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\Page;

/**
 * Class Index
 * @package Phpro\Scheduler\Controller\Adminhtml\JobConfiguration
 */
class Index extends BackendAction
{
    const ADMIN_RESOURCE = 'Phpro_Scheduler::job_configuration';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('Phpro_Scheduler::jobconfiguration');
        $resultPage->addBreadcrumb(__('Cron Schedule'), __('Job Configuration'));
        $resultPage->getConfig()->getTitle()->prepend(__('Job Configuration'));

        return $resultPage;
    }
}
