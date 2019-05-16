<?php
/**
 * @author Volodymyr Vygovskyi
 * @copyright Copyright (c) 2019 Volodymyr Vygovskyi
 * @package Vovsky_ExtensionAttribute
 */

namespace Vovsky\ExtensionAttribute\Service;

use Magento\Framework\App\ResourceConnection;

/**
 * Class GetLastOrderDateByProductService
 */
class GetLastOrderDateByProductService
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * GetLastOrderDateByProductService constructor
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Get last order date
     *
     * @param int $productId
     * @return string
     */
    public function execute($productId)
    {
        if ($productId) {
            $orderItemTable = $this->resourceConnection->getTableName('sales_order_item');
            $select = $this->resourceConnection->getConnection()
                ->select()
                ->from($orderItemTable, ['created_at'])
                ->where('product_id = ?', $productId)
                ->order('created_at desc')
                ->limit(1);

            return $this->resourceConnection->getConnection()->fetchOne($select);
        }

        return '';
    }
}
