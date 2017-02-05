<?php

namespace Predictator\AssociationRule;


class AssociationModel implements AssociationModelInterface
{
	/**
	 * @var Result[]
	 */
	private $result = [];

	/**
	 * @param ProductInterface $product
	 * @param array $result
	 */
	public function addResult(ProductInterface $product, array $result)
	{
		$this->result[$product->getId()] = $result;
	}

	/**
	 * @param ProductInterface $product
	 * @return array|Result[]
	 */
	public function getResult(ProductInterface $product): array
	{
		return $this->result[$product->getId()] ?? array();
	}
}