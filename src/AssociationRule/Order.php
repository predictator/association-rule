<?php

namespace Predictator\AssociationRule;


class Order implements OrderInterface
{
	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var OrderItemInterface[]
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
	 * @param OrderItemInterface $orderItem
	 */
	public function addOrderItem(OrderItemInterface $orderItem)
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
	 * @return OrderItemInterface[]
	 */
	public function getOrderItems(): array
	{
		return $this->orderItems;
	}
}