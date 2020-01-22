<?php

namespace Stalwart\Sweda\File;

use Magento\Framework\Filesystem\DriverInterface;


class Uploader extends \Magento\Framework\File\Uploader
{
    
    private function _setUploadFileId($fileId)
    {
        if (is_array($fileId)) {
            $this->_uploadType = self::MULTIPLE_STYLE;
            $this->_file = $fileId;
        } else {
            if (empty($_FILES)) {
                throw new \Exception('$_FILES array is empty');
            }

            preg_match("/^(.*?)\[(.*?)\]$/", $fileId, $file);
            
            if (is_array($file) && count($file) > 0 && !empty($file[0]) && !empty($file[1])) {
                array_shift($file);
                $this->_uploadType = self::MULTIPLE_STYLE;

                $fileAttributes = $_FILES[$file[0]];
                $tmpVar = [];

                foreach ($fileAttributes as $attributeName => $attributeValue) {
                    $tmpVar[$attributeName] = $attributeValue[$file[1]];
                }

                $fileAttributes = $tmpVar;
                $this->_file = $fileAttributes;
            } elseif (!empty($fileId) && isset($_FILES[$fileId])) {
                $this->_uploadType = self::SINGLE_STYLE;
                $this->_file = $_FILES[$fileId];
            } elseif ($fileId == '') {
                throw new \Exception('Invalid parameter given. A valid $_FILES[] identifier is expected.');
            }
        }
    }

}