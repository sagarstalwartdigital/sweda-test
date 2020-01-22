<?php

/**
 * Copyright © 2017-2018 AppJetty. All rights reserved.
 */

namespace Biztech\AdvancedFonts\Controller\Adminhtml\Fonts;
header("Access-Control-Allow-Origin: *");
class Save extends \Biztech\Productdesigner\Controller\Adminhtml\Fonts {

    public function execute() {
        if ($this->getRequest()->getPostValue()) {
            try {
                $model = $this->fontsFactory->create();
                $data = $this->getRequest()->getPostValue();
                $inputFilter = new \Zend_Filter_Input(
                        [], [], $data
                );
                $data = $inputFilter->getUnescaped();
                $id = $this->getRequest()->getParam('id');
                if (isset($data['stores'])) {
                    if (in_array('0', $data['stores'])) {
                        $data['store_id'] = '0';
                    } else {
                        $data['store_id'] = implode(",", $data['stores']);
                    }
                    unset($data['stores']);
                }

                $fontCollection = $model->getCollection();
                foreach ($fontCollection as $fontData) {
                    if ($fontData->getId() != $id && $fontData->getFontLabel() == trim($data['font_label'])) {
                        $this->messageManager->addError(__('This font label already exists.'));
                        $this->_redirect('productdesigner/fonts');
                        return;
                    }
                }
                $fileData = $this->getRequest()->getFiles('font_image');
                $font_imageData = '';
                if (isset($fileData['name']) && $fileData['name'] != '') {
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'font_image']);
                    $font_imageData = $uploader->validateFile();
                }
                if (isset($font_imageData['name']) && $font_imageData['name'] != '') {
                    $file_name = $font_imageData['name'];
                    $file_size = $font_imageData['size'];
                    $file_tmp = $font_imageData['tmp_name'];
                    $file_type = $font_imageData['type'];
                    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                    $supportedExtension = array('jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG');
                    if (!in_array($file_ext, $supportedExtension) && $file_name) {
                        throw new \Magento\Framework\Exception\LocalizedException(__("This file type is not supported. Please add from supported file types like:'jpg', 'jpeg', 'gif', 'png'"));
                    }
                    $data['image_upload_new'] = [
                        'name' => $file_name,
                        'type' => $file_type,
                        'tmp_name' => $file_tmp,
                        'error' => 0,
                        'size' => $file_size
                    ];
                }
                if ($id) {
                    unset($data['font_file']);
                }
                if (isset($font_imageData['name']) && $font_imageData['name'] != '') {

                    try {
                        $uploader = $this->_fileUploaderFactory->create(['fileId' => $data['image_upload_new']]);
                        $reader = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG']);
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);
                        $path = $reader->getAbsolutePath() . "productdesigner/fonts/images/";

                        $result1 = $uploader->save($path);
                        $data['font_image'] = "productdesigner/fonts/images/" . $result1['file'];
                    } catch (\Exception $e) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                } else {
                    if (isset($data['font_image']['delete'])) {
                        $data['font_image'] = '';
                    } else {
                        unset($data['font_image']);
                    }
                }
                $font_file = $this->getRequest()->getFiles('font_file');
                $font_fileData = '';
                if (isset($font_file['name']) && $font_file['name'] != '') {
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'font_file']);
                    $font_fileData = $uploader->validateFile();
                }
                if (isset($font_fileData['name']) && $font_fileData['name'] != '') {
                    $file_name = $font_fileData['name'];
                    $file_size = $font_fileData['size'];
                    $file_tmp = $font_fileData['tmp_name'];
                    $file_type = $font_fileData['type'];
                    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                    $supportedExtension = array('ttf', 'otf', 'TTF', 'OTF');
                    if (!in_array($file_ext, $supportedExtension)) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('Allowed Font Types are TTF & OTF only.'));
                    }
                    $data['image_upload_new'] = [
                        'name' => $file_name,
                        'type' => $file_type,
                        'tmp_name' => $file_tmp,
                        'error' => 0,
                        'size' => $file_size
                    ];
                    try {
                        $uploader = $this->_fileUploaderFactory->create(['fileId' => $data['image_upload_new']]);
                        $reader = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                        $uploader->setAllowedExtensions(['ttf', 'otf', 'TTF', 'OTF']);
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);
                        $path = $reader->getAbsolutePath() . "productdesigner/fonts/";

                        $result = $uploader->save($path);
                        $data['font_file'] = "productdesigner/fonts/" . $result['file'];
                    } catch (\Exception $e) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                } else {
                    if (isset($data['font_file']['delete'])) {
                        $data['font_file'] = '';
                    } else {
                        unset($data['font_file']);
                    }
                }


                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
                $model->setData($data)->setId($id);
                $this->session->setPageData($model->getData());
                $model->save();
                $this->messageManager->addSuccess(__('Font was successfully saved.'));
                $this->session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('advancedfonts/fonts/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('productdesigner/fonts/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int) $this->getRequest()->getParam('id');
                if (!empty($id)) {
                    $this->_redirect('advancedfonts/fonts/edit', ['id' => $id]);
                } else {
                    $this->_redirect('advancedfonts/fonts/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                        __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->logger->critical($e);
                $this->session->setPageData($data);
                $this->_redirect('advancedfonts/fonts/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->_redirect('productdesigner/fonts/');
    }

}
