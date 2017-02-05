<?php
namespace Predictator\AssociationRule;

interface OrderInterface
{
	/**
	 * @return string
	 */
	public function getId() : string;

	/**
	 * @return ProductInterface[]
	 */
	public function getOrderProducts() : array;
}