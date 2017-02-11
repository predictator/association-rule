<?php

namespace Predictator\AssociationRule;


interface AssociationModelInterface
{

	/**
	 * @param ProductInterface $product
	 * @param ResultSet $result
	 * @return void
	 */
	public function addResult(ProductInterface $product, ResultSet $result);

	/**
	 * @param ProductInterface $product
	 * @return ResultSet
	 */
	public function getResult(ProductInterface $product) : ResultSet;
}