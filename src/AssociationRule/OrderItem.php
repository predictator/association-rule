<?php

namespace Predictator\AssociationRule;


class OrderItem implements OrderItemInterface
{
	/**
	 * @var string
	 */
	private $itemId;

	/**
	 * @param string $itemId
	 */
	public function __construct(string $itemId)
	{
		$this->itemId = $itemId;
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->itemId;
	}
}