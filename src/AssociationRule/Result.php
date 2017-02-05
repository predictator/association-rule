<?php

namespace Predictator\AssociationRule;


class Result implements ProductInterface
{
	/**
	 * @var ProductInterface
	 */
	private $product;

	/**
	 * @var int
	 */
	private $percent;

	/**
	 * @param ProductInterface $product
	 * @param int $percent
	 */
	public function __construct(ProductInterface $product, int $percent)
	{
		$this->product = $product;
		$this->percent = $percent;
	}

	/**
	 * @return int
	 */
	public function getAssociationPercent() :int
	{
		return $this->percent;
	}

	/**
	 * @return ProductInterface
	 */
	public function getProduct() :ProductInterface
	{
		return $this->product;
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->product->getId();
	}
}