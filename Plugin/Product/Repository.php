<?php
/**
 * @author Volodymyr Vygovskyi
 * @copyright Copyright (c) 2019 Volodymyr Vygovskyi
 * @package Vovsky_ExtensionAttribute
 */

namespace Vovsky\ExtensionAttribute\Plugin\Product;

use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchResults;
use Vovsky\ExtensionAttribute\Api\Data\SalesInformationInterface;
use Vovsky\ExtensionAttribute\Api\Data\SalesInformationInterfaceFactory;

class Repository
{
    /**
     * @var SalesInformationInterfaceFactory
     */
    private $salesInformationFactory;

    /**
     * @var ProductExtensionFactory
     */
    private $productExtensionFactory;

    /**
     * Repository constructor.
     *
     * @param ProductExtensionFactory $productExtensionFactory
     * @param SalesInformationInterfaceFactory $salesInformationInterfaceFactory
     */
    public function __construct(
        ProductExtensionFactory $productExtensionFactory,
        SalesInformationInterfaceFactory $salesInformationInterfaceFactory
    ) {
        $this->salesInformationFactory = $salesInformationInterfaceFactory;
        $this->productExtensionFactory = $productExtensionFactory;
    }

    /**
     * @param ProductRepositoryInterface $subject
     * @param SearchResults $searchResult
     * @return SearchResults
     */
    public function afterGetList(
        ProductRepositoryInterface $subject,
        SearchResults $searchResult
    ) {
        /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
        foreach ($searchResult->getItems() as $product) {
            $this->addSalesInformationToProduct($product);
        }

        return $searchResult;
    }

    /**
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $product
     * @return ProductInterface
     */
    public function afterGetById(
        ProductRepositoryInterface $subject,
        ProductInterface $product
    ) {
        $this->addSalesInformationToProduct($product);

        return $product;
    }

    /**
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $product
     * @return ProductInterface
     */
    public function afterGet(
        ProductRepositoryInterface $subject,
        ProductInterface $product
    ) {
        $this->addSalesInformationToProduct($product);

        return $product;
    }

    /**
     * Set sales information and extension attributes
     *
     * @param ProductInterface $product
     * @return $this
     */
    private function addSalesInformationToProduct(ProductInterface $product)
    {
        $extensionAttributes = $product->getExtensionAttributes();

        if (empty($extensionAttributes)) {
            $extensionAttributes = $this->productExtensionFactory->create();
        }
        /** @var SalesInformationInterface $salesInformation */
        $salesInformation = $this->salesInformationFactory->create();
        $salesInformation->setProductId($product->getId());
        $extensionAttributes->setSalesInformation($salesInformation);
        $product->setExtensionAttributes($extensionAttributes);

        return $this;
    }
}
