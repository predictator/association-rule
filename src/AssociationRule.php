<?php

namespace Predictator;

use Predictator\AssociationRule\OrderInterface;

class AssociationRule
{
	/**
	 * @var array
	 */
	private $orderMap = array();

	public function addOrder(OrderInterface $order)
	{
		$orderId = $order->getId();
		if ($orderId === '') {
			throw new \InvalidArgumentException(sprintf('Bad order id: %s', $orderId));
		}
		if (!isset($this->orderMap[$orderId])) {
			$this->orderMap[$orderId] = [];
		}

		foreach ($order->getOrderItems() as $orderItem) {
			$productId = $orderItem->getId();
			if (!in_array($productId, $this->orderMap[$orderId])) {
				$this->orderMap[$orderId][] = $productId;
			}
		}
	}

	/**
	 * @return array
	 */
	public function getOrderIds()
	{
		return array_keys($this->orderMap);
	}
}