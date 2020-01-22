<?php
/**
 * Copyright © 2017-2018 AppJetty. All rights reserved.
 */

namespace Biztech\AdvancedFonts\Controller\Adminhtml\Fonts;
class Delete extends \Biztech\Productdesigner\Controller\Adminhtml\Fonts 
{
    public function execute()
    {

        $id = $this->getRequest()->getParam('id');
        $dirImg = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();
        if ($id) {
            try {
                
                $model = $this->fontsFactory->create();
                $model->load($id);

                if($model->getFontFile()) {
                    $fontFile = $dirImg . $model->getFontFile();                    
                    if(file_exists($fontFile)) unlink($fontFile);
                }
                if($model->getFontImage()) {
                    $fontImage = $dirImg . $model->getFontImage();
                    if(file_exists($fontImage)) unlink($fontImage);
                }

                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the item.'));
                $this->_redirect('productdesigner/fonts/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                $this->logger->critical($e);
                $this->_redirect('productdesigner/fonts/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        $this->_redirect('productdesigner/fonts/');
    }
}
