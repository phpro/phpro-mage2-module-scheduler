<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Controller\Adminhtml\Timeline;

use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends BackendAction
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Phpro_Scheduler::timeline');
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page
        $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Phpro_Scheduler::timeline');
        $resultPage->addBreadcrumb(__('Schedule Timeline'), __('Schedule Timeline'));
        $resultPage->getConfig()->getTitle()->prepend(__('Schedule Timeline'));

        return $resultPage;
    }
}
