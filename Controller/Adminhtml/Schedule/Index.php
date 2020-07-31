<?php
namespace Phpro\Scheduler\Controller\Adminhtml\Schedule;

use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\Page;

/**
 * Class Index
 * @package Phpro\Scheduler\Controller\Adminhtml\Schedule
 */
class Index extends BackendAction
{
    const ADMIN_RESOURCE = 'Phpro_Scheduler::schedule';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context     $context
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

        $resultPage->setActiveMenu('Phpro_Scheduler::schedule');
        $resultPage->addBreadcrumb(__('Schedule List'), __('Schedule List'));
        $resultPage->getConfig()->getTitle()->prepend(__('Schedule List'));

        return $resultPage;
    }
}
