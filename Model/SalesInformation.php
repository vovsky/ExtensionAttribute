<?php
/**
 * @author Volodymyr Vygovskyi
 * @copyright Copyright (c) 2019 Volodymyr Vygovskyi
 * @package Vovsky_ExtensionAttribute
 */

namespace Vovsky\ExtensionAttribute\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Ui\Model\ResourceModel\Bookmark as ResourceBookmark;
use Magento\Ui\Model\ResourceModel\Bookmark\Collection;
use Vovsky\ExtensionAttribute\Api\Data\SalesInformationExtensionInterface;
use Vovsky\ExtensionAttribute\Api\Data\SalesInformationInterface;
use Vovsky\ExtensionAttribute\Service\GetLastOrderDateByProductService;
use Vovsky\ExtensionAttribute\Service\GetProductQtyOrderedService;

/**
 * Class SalesInformation
 */
class SalesInformation extends AbstractExtensibleModel implements SalesInformationInterface
{
    /**
     * @var GetProductQtyOrderedService
     */
    private $getProductQtyOrderedService;

    /**
     * @var GetLastOrderDateByProductService
     */
    private $getLastOrderDateByProductService;

    /**
     * @var string|null
     */
    private $orderStatus;

    /**
     * SalesInformation constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param ResourceBookmark $resource
     * @param Collection $resourceCollection
     * @param null $orderStatus
     * @param GetLastOrderDateByProductService $getLastOrderWithProductService
     * @param GetProductQtyOrderedService $getProductQtyOrderedService
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        ResourceBookmark $resource,
        Collection $resourceCollection,
        GetLastOrderDateByProductService $getLastOrderWithProductService,
        GetProductQtyOrderedService $getProductQtyOrderedService,
        $orderStatus = null,
        array $data = []
    ) {
        $this->orderStatus = $orderStatus;
        $this->getLastOrderDateByProductService = $getLastOrderWithProductService;
        $this->getProductQtyOrderedService = $getProductQtyOrderedService;

        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Get qty of ordered products
     *
     * @return float
     */
    public function getQty()
    {
        return $this->getProductQtyOrderedService->execute($this->getProductId(), $this->orderStatus);
    }

    /**
     * Get last order date
     *
     * @return string
     */
    public function getLastOrder()
    {
        return $this->getLastOrderDateByProductService->execute($this->getProductId());
    }

    /**
     * {@inheritdoc}
     *
     * @return SalesInformationExtensionInterface|Null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     *
     * @param SalesInformationExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(SalesInformationExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get product id
     *
     * @return int|null
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * @param $productId
     *
     * @return void
     */
    public function setProductId($productId)
    {
        $this->setData(self::PRODUCT_ID, $productId);
    }
}
