<?php

namespace Predictator;

use Predictator\AssociationRule\AssociationModel;
use Predictator\AssociationRule\AssociationModelInterface;
use Predictator\AssociationRule\OrderInterface;
use Predictator\AssociationRule\ProductInterface;
use Predictator\AssociationRule\Result;
use Predictator\AssociationRule\ResultSet;

class AssociationRule
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
	 * @return ResultSet
	 */
	public function getResult(ProductInterface $product) : ResultSet
	{
		$productId = $product->getId();
		$orderIds = $this->getOrderIds($productId);
		$orderIdsCount = count($orderIds);

		$ordersProducts = $this->getOrderProducts($orderIds, $productId);

		$associatedProducts = array();
		foreach ($ordersProducts as $orderProductId) {
			$ratio = (count(array_intersect($this->getOrderIds($orderProductId), $orderIds)) / $orderIdsCount) * 100;
			if ($ratio) {
				$associatedProducts[$orderProductId] = $ratio;
			}
		}

		arsort($associatedProducts);

		$resultSet = new ResultSet();
		foreach ($associatedProducts as $productId => $ratio) {
			$resultSet->addResult(new Result($this->products[$productId], $ratio));
		}
		return $resultSet;
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
	 * @param AssociationModel $associationModel
	 * @return AssociationModelInterface
	 */
	public function exportModel(AssociationModel $associationModel) : AssociationModelInterface
	{
		foreach ($this->products as $product) {
			$associationModel->addResult($product, $this->getResult($product));
		}

		return $associationModel;
	}

	/**
	 * @param array $orderIds
	 * @param string $productId
	 * @return array
	 */
	private function getOrderProducts(array $orderIds, string $productId): array
	{
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
		return $ordersProducts;
	}
}