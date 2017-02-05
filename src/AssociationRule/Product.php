<?php

namespace Predictator\AssociationRule;


class Product implements ProductInterface
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