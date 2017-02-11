<?php

namespace Predictator\AssociationRule;


class AssociationModel implements AssociationModelInterface
{
	/**
	 * @var ResultSet[]
	 */
	private $result = [];

	/**
	 * @param ProductInterface $product
	 * @param ResultSet $result
	 */
	public function addResult(ProductInterface $product, ResultSet $result)
	{
		$this->result[$product->getId()] = $result;
	}

	/**
	 * @param ProductInterface $product
	 * @return ResultSet
	 */
	public function getResult(ProductInterface $product): ResultSet
	{
		return $this->result[$product->getId()] ?? new ResultSet();
	}
}