<?php

namespace Biztech\AdvancedFonts\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Archive\Zip as ZipArchive;

class InstallData implements InstallDataInterface {

    private $eavSetupFactory;
    protected $_directoryList;
    protected $clipartCategoriesCollection;
    protected $_fileSystem;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList, \Biztech\Productdesigner\Model\Mysql4\Clipart\CollectionFactory $clipartCategoriesCollection, \Magento\Framework\Filesystem $filesystem
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->_directoryList = $directoryList;
        $this->clipartCategoriesCollection = $clipartCategoriesCollection;
        $this->_fileSystem = $filesystem;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]); 
        $this->storeFontData($setup);
    }

    protected function storeCsvData($setup, $fileName, $tableName) {
        $fileData = array_map('str_getcsv', file($fileName));
        $tableFields = array_shift($fileData);
        $count = 0;
        $val = implode($tableFields, ",");
        foreach ($fileData as $parentKey => $tableData) {
            $count = 0;            
            $data = array();
            foreach ($tableData as $childKey => $tableFieldValue) {           
                $fieldName = $tableFields[$count++];
                $data[$fieldName] = $tableFieldValue;
            }
            if (count($data)) {
                $setup->getConnection()
                ->insertMultiple($setup->getTable($tableName), $data);
            }
        }
    }

    protected function storeFontData($setup) {
        $appPath = $this->_directoryList->getPath('app');
        $path = $appPath . "/code/Biztech/AdvancedFonts/SampleData/fonts";
        $fontData = $path . "/add-on.csv";
        $fontTableName = "productdesigner_fonts";
        if(file_exists($fontData)){
            $this->storeCsvData($setup, $fontData, $fontTableName);
            $file = $path . "/add-on.zip";
            $reader = $this->_fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
            $path =$reader->getAbsolutePath();
            $extractPath = $reader->getAbsolutePath() . 'productdesigner/';
            if(!is_dir($path. 'productdesigner')){
                mkdir($path . "productdesigner");
            }
            if (!is_dir($extractPath . "fonts")) {
                mkdir($extractPath . "fonts", 0777);
            }
            $this->unpack($file, $extractPath. "fonts");
        }
    }

    /**
     * Unpack file.
     *
     * @param string $source
     * @param string $destination
     *
     * @return string
     */
    public function unpack($source, $destination)
    {
        $zip = new \ZipArchive();
        $res = $zip->open($source);
        if ($res === TRUE) {
          $zip->extractTo($destination);
          $zip->close();
        }
    }

}
