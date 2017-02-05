<?php

namespace Predictator\AssociationRule;


class Order implements OrderInterface
{
	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var ProductInterface[]
	 */
	private $orderItems = array();

	/**
	 * @param string $id
	 */
	public function __construct(string $id)
	{
		$this->id = $id;
	}

	/**
	 * @param ProductInterface $orderItem
	 */
	public function addOrderItem(ProductInterface $orderItem)
	{
		$this->orderItems[] = $orderItem;
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @return ProductInterface[]
	 */
	public function getOrderProducts(): array
	{
		return $this->orderItems;
	}
}