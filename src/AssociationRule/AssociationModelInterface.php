<?php

namespace Predictator\AssociationRule;


interface AssociationModelInterface
{

	/**
	 * @param ProductInterface $product
	 * @param array $result
	 * @return void
	 */
	public function addResult(ProductInterface $product, array $result);

	/**
	 * @param ProductInterface $product
	 * @return array|Result[]
	 */
	public function getResult(ProductInterface $product) : array;
}