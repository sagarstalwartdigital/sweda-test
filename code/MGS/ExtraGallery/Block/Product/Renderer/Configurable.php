<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MGS\ExtraGallery\Block\Product\Renderer;

/**
 * Swatch renderer block
 *
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 100.0.2
 */
class Configurable extends \Magento\Swatches\Block\Product\Renderer\Configurable
{
    /**
     * Get product images for configurable variations
     *
     * @return array
     * @since 100.2.0
     */
    protected function getOptionImages()
    {
        $images = [];
        $parentImages = [];
        $parentPosition = 1;
        $inviProductImages = $this->helper->getGalleryImages($this->getProduct());
        foreach ($inviProductImages as $inviImage) {
            $parentImages[] =
                        [
                            'thumb' => $inviImage->getData('small_image_url'),
                            'img' => $inviImage->getData('medium_image_url'),
                            'full' => $inviImage->getData('large_image_url'),
                            'zoom' => $inviImage->getData('zoom_image_url'),
                            'caption' => $inviImage->getLabel(),
                            'position' => $parentPosition,//$inviImage->getPosition(),
                            'isMain' => false,
                            'media_type' => $inviImage->getMediaType(),
                            'videoUrl' => $inviImage->getVideoUrl(),
                        ];
        }
        $colors = array();
        foreach ($this->getAllowProducts() as $product) {
            foreach ($parentImages as $imageArray) {
                $images[$product->getId()][] = $imageArray;
            }
            $position = $parentPosition;
            $productImages = $this->helper->getGalleryImages($product) ?: [];
            foreach ($productImages as $image) {
                $images[$product->getId()][] =
                [
                    'thumb' => $image->getData('small_image_url'),
                    'img' => $image->getData('medium_image_url'),
                    'full' => $image->getData('large_image_url'),
                    'zoom' => $image->getData('zoom_image_url'),
                    'caption' => $image->getLabel(),
                    'position' => $position,//$image->getPosition(),
                    'isMain' => $image->getFile() == $product->getImage(),
                    'media_type' => $image->getMediaType(),
                    'videoUrl' => $image->getVideoUrl(),
                ];
                $position++;
                $colors[$product->getId()][$product->getColor()] = $product->getColor();
            }
            foreach ($this->getAllowProducts() as $inviPproduct) {
                if(!(isset($colors[$product->getId()][$inviPproduct->getColor()])))
                {
                    $inviProductImages = $this->helper->getGalleryImages($inviPproduct) ?: [];
                    foreach ($inviProductImages as $inviImage) {
                        $images[$product->getId()][] =
                        [
                            'thumb' => $inviImage->getData('small_image_url'),
                            'img' => $inviImage->getData('medium_image_url'),
                            'full' => $inviImage->getData('large_image_url'),
                            'zoom' => $inviImage->getData('zoom_image_url'),
                            'caption' => $inviImage->getLabel(),
                            'position' => $position,//$inviImage->getPosition(),
                            'isMain' => $inviImage->getFile() == $product->getImage(),
                            'media_type' => $inviImage->getMediaType(),
                            'videoUrl' => $inviImage->getVideoUrl(),
                        ];
                        $position++;
                    }
                    $colors[$product->getId()][$inviPproduct->getColor()] = $inviPproduct->getColor();
                }
            }
        }

        return $images;
    }
}
