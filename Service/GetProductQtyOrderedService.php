<?php
/**
 * @author Volodymyr Vygovskyi
 * @copyright Copyright (c) 2019 Volodymyr Vygovskyi
 * @package Vovsky_ExtensionAttribute
 */

namespace Vovsky\ExtensionAttribute\Service;

use Magento\Framework\App\ResourceConnection;

/**
 * Class GetProductQtyOrderedService
 */
class GetProductQtyOrderedService
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * GetProductQtyOrderedService constructor
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Get qty of products ordered
     *
     * @param int $productId
     * @return float
     */
    public function execute($productId, $orderStatus)
    {
        if ($productId) {
            $orderItemTable = $this->resourceConnection->getTableName('sales_order_item');
            $select = $this->resourceConnection->getConnection()
                ->select()
                ->from(['order_item_table' => $orderItemTable], ['qty' => 'sum(qty_ordered)'])
                ->where('product_id = ?', $productId);
            if ($orderStatus) {
                $orderTable = $this->resourceConnection->getTableName('sales_order');
                $select->join(
                    ['order_table' => $orderTable],
                    'order_item_table.order_id = order_table.entity_id',
                    []
                )->where('order_table.status = ?', $orderStatus);
            }

            return $this->resourceConnection->getConnection()->fetchOne($select);
        }

        return 0;
    }
}
