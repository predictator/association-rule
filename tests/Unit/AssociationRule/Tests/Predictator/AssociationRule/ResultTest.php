<?php

namespace Tests\Predictator\AssociationRule;


use Predictator\AssociationRule\Product;
use Predictator\AssociationRule\Result;


class ResultTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @test
	 */
	public function getPercent()
	{
		$result = new Result(new Product(15), 10);
		$this->assertEquals(10, $result->getAssociationPercent());
	}

	/**
	 * @test
	 */
	public function getProductId()
	{
		$result = new Result(new Product(15), 10);
		$this->assertEquals(15, $result->getId());
	}

	/**
	 * @test
	 */
	public function getProduct()
	{
		$product = new Product(15);
		$result = new Result($product, 10);
		$this->assertEquals($product, $result->getProduct());
	}
}
