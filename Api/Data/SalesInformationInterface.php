<?php
/**
 * @author Volodymyr Vygovskyi
 * @copyright Copyright (c) 2019 Volodymyr Vygovskyi
 * @package Vovsky_ExtensionAttribute
 */

namespace Vovsky\ExtensionAttribute\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface SalesInformationInterface extends ExtensibleDataInterface
{
    const PRODUCT_ID = 'product_id';

    /**
     * @return float
     */
    public function getQty();

    /**
     * @return string
     */
    public function getLastOrder();

    /**
     * @return int
     */
    public function getProductId();

    /**
     * @param $productId
     * @return void
     */
    public function setProductId($productId);

    /**
     * Retrieve existing extension attributes object.
     *
     * @return SalesInformationExtensionInterface $extensionAttributes
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param SalesInformationExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        SalesInformationExtensionInterface $extensionAttributes
    );
}
