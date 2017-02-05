<?php

namespace Predictator\AssociationRule;


interface AssociationModelInterface
{
	/**
	 * @param ProductInterface $product
	 * @return array|Result[]
	 */
	public function getResult(ProductInterface $product) : array;
}