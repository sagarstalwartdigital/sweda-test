<?php

namespace Biztech\PrintingMethods\Controller\Adminhtml\Colors;
header("Access-Control-Allow-Origin: *");
class Save extends \Biztech\PrintingMethods\Controller\Adminhtml\Colors
{   
    protected $_colorsModel;    
    public function __construct(\Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Biztech\PrintingMethods\Model\Colors $colorsModel,        
        \Psr\Log\LoggerInterface $loggerInterface
    ) {
        $this->_colorsModel = $colorsModel;        
        $this->_loggerInterface = $loggerInterface;
        parent::__construct(
          $context, $coreRegistry, $resultForwardFactory, $resultPageFactory
      );
    }

    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $model = $this->_colorsModel;

                $postData = $this->getRequest()->getPostValue();
                $inputFilter = new \Zend_Filter_Input(
                    [],
                    [],
                    $postData
                );
                $postData = $inputFilter->getUnescaped();
                $id = $this->getRequest()->getParam('id');                
                
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
                $model->setData($postData)->setId($id);
                $session = $this->_session;
                $session->setPageData($model->getData());
                $model->save();
                $this->messageManager->addSuccess(__('Color Counter was successfully saved.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('productdesigner/colors/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('productdesigner/colors/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                if (!empty($id)) {
                    $this->_redirect('productdesigner/colors/edit', ['id' => $id]);
                } else {
                    $this->_redirect('productdesigner/colors/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->_loggerInterface->critical($e);
                $this->_session->setPageData($postData);
                $this->_redirect('productdesigner/colors/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->_redirect('productdesigner/colors/');
    }
}
