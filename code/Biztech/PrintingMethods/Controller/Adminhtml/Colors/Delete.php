<?php

namespace Biztech\PrintingMethods\Controller\Adminhtml\Colors;
header("Access-Control-Allow-Origin: *");
class Delete extends \Biztech\PrintingMethods\Controller\Adminhtml\Colors
{
    protected $_colorsModel;
    protected $_loggerInterface;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Biztech\PrintingMethods\Model\Colors $colorsModel,
        \Psr\Log\LoggerInterface $loggerInterface
    ) {
        $this->_colorsModel = $colorsModel;
        $this->_loggerInterface = $loggerInterface;
        parent::__construct($context, $coreRegistry, $resultForwardFactory, $resultPageFactory);
    }

    
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->_colorsModel;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the item.'));
                $this->_redirect('productdesigner/colors/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                $this->_loggerInterface->critical($e);
                $this->_redirect('productdesigner/colors/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        $this->_redirect('productdesigner/colors/');
    }
}
