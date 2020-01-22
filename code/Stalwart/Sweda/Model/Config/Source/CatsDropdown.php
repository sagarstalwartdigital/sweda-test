<?php
/**
 * My own options
 *
 */
namespace Stalwart\Sweda\Model\Config\Source;
class CatsDropdown implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $categoryFactory = $objectManager->create('Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
        $categoryCollection = $categoryFactory->create()
            ->addAttributeToSelect(array('id','name'))
            ->addAttributeToFilter('is_active','1');
        $options = array();
        foreach($categoryCollection as $category){
                $options[] = array(
                   'label' => $category->getName(),
                   'value' => $category->getId()
                );
        }
        return $options;
    }
}

?>