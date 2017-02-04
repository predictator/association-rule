<?php
namespace Predictator\AssociationRule;

interface OrderInterface
{
	/**
	 * @return string
	 */
	public function getId() : string;

	/**
	 * @return OrderItemInterface[]
	 */
	public function getOrderItems() : array;
}