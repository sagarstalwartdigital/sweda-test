<?php

/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Productdesigner\Controller\Cart;

class CouponPost extends \Magento\Checkout\Controller\Cart\CouponPost {

	/**
	 * Sales quote repository
	 *
	 * @var \Magento\Quote\Api\CartRepositoryInterface
	 */
	protected $quoteRepository;

	/**
	 * Coupon factory
	 *
	 * @var \Magento\SalesRule\Model\CouponFactory
	 */
	protected $couponFactory;

	/**
	 * Initialize coupon
	 *
	 * @return \Magento\Framework\Controller\Result\Redirect
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function execute() {
		$couponCode = $this->getRequest()->getParam('remove') == 1 ? '' : trim($this->getRequest()->getParam('coupon_code'));

		$cartQuote = $this->cart->getQuote();
		$oldCouponCode = $cartQuote->getCouponCode();

		$codeLength = strlen($couponCode);
		if (!$codeLength && !strlen($oldCouponCode)) {
			return $this->_goBack();
		}

		try {
			$isCodeLengthValid = $codeLength && $codeLength <= \Magento\Checkout\Helper\Cart::COUPON_CODE_MAX_LENGTH;
			$itemsCount = $cartQuote->getItemsCount();
			if ($itemsCount) {
				$cartQuote->getShippingAddress()->setCollectShippingRates(true);
				$cartQuote->setCouponCode($isCodeLengthValid ? $couponCode : '')->collectTotals();
				$this->quoteRepository->save($cartQuote);
			}
			if ($codeLength) {
				$escaper = $this->_objectManager->get('Magento\Framework\Escaper');
				if (!$itemsCount) {
					if ($isCodeLengthValid) {
						$coupon = $this->couponFactory->create();
						$coupon->load($couponCode, 'code');
						if ($coupon->getId()) {
							$this->_checkoutSession->getQuote()->setCouponCode($couponCode)->save();
							$this->messageManager->addSuccess(
								__(
									'You used coupon code "%1".', $escaper->escapeHtml($couponCode)
								)
							);
						} else {
							$this->messageManager->addError(
								__(
									'The coupon code "%1" is not valid.', $escaper->escapeHtml($couponCode)
								)
							);
						}
					} else {
						$this->messageManager->addError(
							__(
								'The coupon code "%1" is not valid.', $escaper->escapeHtml($couponCode)
							)
						);
					}
				} else {
					if ($isCodeLengthValid && $couponCode == $cartQuote->getCouponCode()) {
						$this->messageManager->addSuccess(
							__(
								'You used coupon code "%1".', $escaper->escapeHtml($couponCode)
							)
						);
					} else {
						$this->messageManager->addError(
							__(
								'The coupon code "%1" is not valid.', $escaper->escapeHtml($couponCode)
							)
						);
					}
				}
			} else {
				$this->messageManager->addSuccess(__('You canceled the coupon code.'));
			}
		} catch (\Magento\Framework\Exception\LocalizedException $e) {
			$this->messageManager->addError($e->getMessage());
		} catch (\Exception $e) {
			$this->messageManager->addError(__('We cannot apply the coupon code.'));
			$this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
		}
		return $this->_goBack();
	}
}
