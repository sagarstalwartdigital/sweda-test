<?php
/**
 * @category   Biztech
 * @package    Biztech_Tooltip
 * @author     employee@gmail.com
 * @copyright  This file was generated by using Module Creator(http://code.vky.co.in/magento-2-module-creator/) provided by VKY <viky.031290@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Biztech\DesignTemplates\Controller\Adminhtml\Designtemplates;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Biztech\DesignTemplates\Model\Mysql4\Designtemplates\CollectionFactory;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    protected $_filesystem;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory, \Magento\Framework\Filesystem $filesystem)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->_filesystem = $filesystem;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection as $record) {
            $templateData = $record->getData();

            /*
            ** Remove Template Images
            */
            if(isset($templateData['image'])){
                $designtemplate_id = $templateData['designtemplates_id'];
                $reader = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();
                $imgPath = $reader.'/productdesigner/templates/'. $designtemplate_id . '/base/' . $templateData['image'];
                if (file_exists($imgPath)) {
                    unlink($imgPath);
                    $baseDir = $reader.'/productdesigner/templates/'. $designtemplate_id . '/base/';
                    $TemplateDir = $reader.'/productdesigner/templates/'. $designtemplate_id;
                    $files = array_diff(scandir($baseDir), array('..', '.'));
                    if (count($files) == 0) {
                         rmdir($baseDir);
                         rmdir($TemplateDir);
                    }
                }
            }

            /*
            ** Remove Template
            */
            $record->delete();
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
