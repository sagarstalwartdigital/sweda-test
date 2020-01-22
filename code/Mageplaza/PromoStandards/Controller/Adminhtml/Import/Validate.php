<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_PromoStandards
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\PromoStandards\Controller\Adminhtml\Import;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Mageplaza\PromoStandards\Helper\Data as PromoStandardsHelper;

/**
 * Class Import
 * @package Mageplaza\PromoStandards\Controller\Adminhtml\Import
 */
class Validate extends Action
{
    /**
     * @var PromoStandardsHelper
     */
    public $promostandardsHelper;

    /**
     * Validate constructor.
     *
     * @param Context $context
     * @param PromoStandardsHelper $promostandardsHelper
     */
    public function __construct(
        Context $context,
        PromoStandardsHelper $promostandardsHelper
    ) {
        $this->promostandardsHelper = $promostandardsHelper;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();

        try {
            $connect = mysqli_connect($data['host'], $data['user_name'], $data['password'], $data['database']);
            $importName = $data['import_name'];

            /** @var \Magento\Backend\Model\Session */
            $this->_getSession()->setData('mageplaza_promostandards_import_data', $data);
            $result = ['import_name' => $importName, 'status' => 'ok'];

            mysqli_close($connect);

            return $this->getResponse()->representJson(PromoStandardsHelper::jsonEncode($result));
        } catch (LocalizedException $e) {
            $result = ['import_name' => $data["import_name"], 'status' => 'false'];

            return $this->getResponse()->representJson(PromoStandardsHelper::jsonEncode($result));
        } catch (\RuntimeException $e) {
            $result = ['import_name' => $data["import_name"], 'status' => 'false'];

            return $this->getResponse()->representJson(PromoStandardsHelper::jsonEncode($result));
        } catch (\Exception $e) {
            $result = ['import_name' => $data["import_name"], 'status' => 'false'];

            return $this->getResponse()->representJson(PromoStandardsHelper::jsonEncode($result));
        }
    }
}
