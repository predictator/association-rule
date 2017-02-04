<?php

namespace Tests\Predictator\Unit;


use Faker\Factory as FakerFaktory;
use Predictator\AssociationRule;


class AssociationRuleTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @param array $orders
	 * @param array $expectedOrderIds
	 * @param array $exceptedProductIds
	 */
	protected function assertOrder(array $orders, array $expectedOrderIds, array $exceptedProductIds = null)
	{
		$assocRule = new AssociationRule();
		foreach ($orders as $order) {
			$assocRule->addOrder($order);
		}

		$this->assertEquals(
			$expectedOrderIds,
			$assocRule->getOrderIds()
		);

		if ($exceptedProductIds) {
			$this->assertEquals(
				$exceptedProductIds,
				$assocRule->getProductIds()
			);
		}
	}

	/**
	 * @tests
	 */
	public function addOrder_sameId()
	{
		$this->assertOrder(
			array(
				new AssociationRule\Order(10),
				new AssociationRule\Order(10)
			),
			array(10)
		);
	}

	/**
	 * @tests
	 */
	public function addOrder_sameObject()
	{
		$order = new AssociationRule\Order(10);
		$this->assertOrder(
			array(
				$order,
				$order
			),
			array(10)
		);
	}

	/**
	 * @tests
	 * @param $orderId
	 * @dataProvider validIds
	 */
	public function addOrder_acceptableOrderIds($orderId)
	{
		$order = new AssociationRule\Order($orderId);
		$this->assertOrder(
			array(
				$order
			),
			array($orderId)
		);
	}

	/**
	 * @tests
	 * @expectedException \InvalidArgumentException
	 */
	public function addOrder_unacceptableOrderIds_empty()
	{
		$order = new AssociationRule\Order('');
		$assocRule = new AssociationRule();
		$assocRule->addOrder($order);
	}

	/**
	 * @tests
	 * @param $orderId
	 * @dataProvider invalidIds
	 * @expectedException \TypeError
	 */
	public function addOrder_unacceptableOrderIds($orderId)
	{
		$order = new AssociationRule\Order($orderId);
		$this->assertOrder(
			array(
				$order
			),
			array($orderId)
		);
	}

	public function getUniqueProductIds()
	{
		$order = new AssociationRule\Order();
		$this->assertOrder(
			array(
				$order
			),
			array($orderId)
		);
	}

	/**
	 * @return array
	 */
	public function validIds()
	{
		$fakerFactory = FakerFaktory::create();
		return array(
			[1],
			[$fakerFactory->uuid],
			[$fakerFactory->randomDigitNotNull],
			[$fakerFactory->randomFloat()],
			[$fakerFactory->randomLetter],
			[$fakerFactory->words(1, true)],
			[$fakerFactory->words(3, true)],
			[$fakerFactory->ean13],
			[$fakerFactory->ean8],
			[$fakerFactory->isbn13],
			[$fakerFactory->isbn10],
			[$fakerFactory->md5],
			[$fakerFactory->sha1],
			[$fakerFactory->sha256],
			[$fakerFactory->isbn10],
			['áűúőóüó'],
			['*.éáúúóˆ1˘ˇˆ˘~*>#*'],
			['/ \ \\ "\'"[]'],
			[0b11001],
			[0x23A3],
		);
	}

	public function invalidIds()
	{
		return array(
			//[''],
			[null],
			[new \stdClass()],
			[0]
		);
	}

}
