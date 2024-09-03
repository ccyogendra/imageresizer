<?php
declare(strict_types=1);

namespace Mageplugins\ResizeImageGraphQl\Model\Resolver\Product\MediaGallery;

use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\ImageFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplugins\ResizeImageGraphQl\Helper\Data as ResizeImageHelper;

/**
 * Returns media URL.
 */
class Url implements ResolverInterface
{
    /**
     * @var ImageFactory
     */
    private $productImageFactory;

    /**
     * @var Image
     */
    private $catalogImageHelper;

    /**
     * @var ResizeImageHelper
     */
    private $helperData;

    /**
     * Constructor.
     *
     * @param ImageFactory $productImageFactory
     * @param Image $catalogImageHelper
     * @param ResizeImageHelper $helperData
     */
    public function __construct(
        ImageFactory $productImageFactory,
        Image $catalogImageHelper,
        ResizeImageHelper $helperData
    ) {
        $this->productImageFactory = $productImageFactory;
        $this->catalogImageHelper = $catalogImageHelper;
        $this->helperData = $helperData;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $args['width'] = $args['width'] ?? $this->helperData->getGeneralConfig('mageplugins_width');
        $args['height'] = $args['height'] ?? $this->helperData->getGeneralConfig('mageplugins_height');

        if (!isset($value['image_type']) && !isset($value['file'])) {
            throw new LocalizedException(__('"image_type" value should be specified'));
        }

        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        /** @var Product $product */
        $product = $value['model'];


        if (isset($value['image_type'])) {

            return $this->getImageUrl($value['image_type'], $product, $args);
        }

        if (isset($value['file'])) {
            $image = $this->productImageFactory->create();
            $image->setDestinationSubdir('image')->setBaseFile($value['file']);
            $image->setWidth($args['width']);
            $image->setHeight($args['height']);
            $image->resize();
            return $image->getUrl();
        }

        return [];
    }

    /**
     * Get image URL.
     *
     * @param string $imageType
     * @param Product $product
     * @param array $imageArgs
     * @return string
     */
    private function getImageUrl(string $imageType, Product $product, array $imageArgs): string
    {
        $width = $imageArgs['width'];
        $height = $imageArgs['height'];
        return $this->catalogImageHelper->init($product, 'product_page_image_large')
            ->setImageFile($product->getData($imageType))
            ->resize($width, $height)
            ->getUrl();
    }
}
