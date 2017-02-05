<?php

namespace Predictator;

use Predictator\AssociationRule\AssociationModel;
use Predictator\AssociationRule\AssociationModelInterface;
use Predictator\AssociationRule\OrderInterface;
use Predictator\AssociationRule\ProductInterface;
use Predictator\AssociationRule\Result;

class AssociationRule implements AssociationModelInterface
{
	/**
	 * @var array
	 */
	private $orderMap = [];

	/**
	 * @var array
	 */
	private $productMap = [];

	/**
	 * @var ProductInterface[]
	 */
	private $products = [];

	/**
	 * @param OrderInterface $order
	 */
	public function addOrder(OrderInterface $order)
	{
		$orderId = $order->getId();
		if (!$orderId) {
			throw new \InvalidArgumentException(sprintf('Bad order id: %s', $orderId));
		}

		$this->addArrayIfKeyNotExists($this->orderMap, $orderId);

		foreach ($order->getOrderProducts() as $orderItem) {
			$productId = $orderItem->getId();
			if (!$productId) {
				throw new \InvalidArgumentException(sprintf('Bad product id: %s', $productId));
			}
			if (!in_array($productId, $this->orderMap[$orderId])) {
				$this->orderMap[$orderId][] = $productId;
				$this->products[$productId] = $orderItem;
			}

			$this->addArrayIfKeyNotExists($this->productMap, $productId);

			if (!in_array($orderId, $this->productMap[$productId])) {
				$this->productMap[$productId][] = $orderId;
			}
		}
	}

	/**
	 * @param array $array
	 * @param string $key
	 */
	private function addArrayIfKeyNotExists(array &$array, string $key)
	{
		if (!isset($array[$key])) {
			$array[$key] = [];
		}
	}

	/**
	 * @param ProductInterface $product
	 * @return array|Result[]
	 */
	public function getResult(ProductInterface $product) : array
	{
		$productId = $product->getId();
		$orderIds = $this->getOrderIds($productId);
		$orderIdsCount = count($orderIds);

		$ordersProducts = [];
		foreach ($orderIds as $orderId) {
			$orderProducts = $this->getProductIds($orderId);
			foreach ($orderProducts as $similarOrderProduct) {
				if (
					!in_array($similarOrderProduct, $ordersProducts) &&
					$productId != $similarOrderProduct
				) {
					$ordersProducts[] = $similarOrderProduct;
				}
			}
		}

		$associatedProducts = array();
		foreach ($ordersProducts as $orderProductId) {
			$ratio = (count(array_intersect($this->getOrderIds($orderProductId), $orderIds)) / $orderIdsCount) * 100;
			if ($ratio) {
				$associatedProducts[$orderProductId] = $ratio;
			}
		}

		arsort($associatedProducts);

		$result = [];
		foreach ($associatedProducts as $productId => $ratio) {
			$result[] = new Result($this->products[$productId], $ratio);
		}
		return $result;
	}

	/**
	 * @param string $id
	 * @return array
	 */
	private function getOrderIds(string $id): array
	{
		return $this->productMap[$id] ?? array();
	}

	/**
	 * @param string $id
	 * @return array
	 */
	private function getProductIds(string $id): array
	{
		return $this->orderMap[$id] ?? array();
	}

	/**
	 * @return AssociationModelInterface
	 */
	public function exportModel() : AssociationModelInterface
	{
		$associationModel = new AssociationModel();
		foreach ($this->products as $product) {
			$associationModel->addResult($product, $this->getResult($product));
		}

		return $associationModel;
	}
}