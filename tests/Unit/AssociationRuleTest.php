<?php

namespace Tests\Predictator\Unit;


use Faker\Factory as FakerFaktory;
use Predictator\AssociationRule;


class AssociationRuleTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @test
	 */
	public function zeroProduct()
	{
		$assoc = new AssociationRule();
		$product = new AssociationRule\Product(10);
		$result = $assoc->getResult($product);

		$this->assertEmpty($result);
	}

	/**
	 * @test
	 */
	public function oneOrderWithoutProduct()
	{
		$assoc = new AssociationRule();
		$assoc->addOrder(new AssociationRule\Order(1));
		$product = new AssociationRule\Product(10);
		$result = $assoc->getResult($product);
		$this->assertEmpty($result);
	}

	/**
	 * @test
	 */
	public function OrderSameProduct()
	{
		$assoc = new AssociationRule();
		$order = new AssociationRule\Order(1);
		$product = new AssociationRule\Product(10);
		$order->addOrderItem($product);
		$assoc->addOrder($order);
		$result = $assoc->getResult($product);
		$this->assertEmpty($result);
	}

	/**
	 * @test
	 */
	public function DoubleOrderSameProduct()
	{
		$assoc = new AssociationRule();
		$order = new AssociationRule\Order(1);
		$product = new AssociationRule\Product(10);
		$order->addOrderItem($product);
		$order->addOrderItem($product);

		$assoc->addOrder($order);
		$assoc->addOrder($order);
		$result = $assoc->getResult($product);
		$this->assertEmpty($result);
	}

	/**
	 * @test
	 */
	public function FullPercent()
	{
		$assoc = new AssociationRule();
		$order1 = new AssociationRule\Order(1);
		$order2 = new AssociationRule\Order(2);
		$product1 = new AssociationRule\Product(10);
		$product2 = new AssociationRule\Product(11);

		$order1->addOrderItem($product1);
		$order1->addOrderItem($product2);

		$order2->addOrderItem($product1);

		$assoc->addOrder($order1);
		$assoc->addOrder($order2);
		$result = $assoc->getResult($product2);

		$this->assertEquals(
			array(
				new AssociationRule\Result($product1, 100)
			),
			$result

		);
	}

	/**
	 * @test
	 */
	public function HalfPercent()
	{
		$assoc = new AssociationRule();
		$order1 = new AssociationRule\Order(1);
		$order2 = new AssociationRule\Order(2);
		$order3 = new AssociationRule\Order(3);

		$product1 = new AssociationRule\Product(10);
		$product2 = new AssociationRule\Product(11);
		$product3 = new AssociationRule\Product(12);

		$order1->addOrderItem($product1);
		$order1->addOrderItem($product2);

		$order2->addOrderItem($product1);

		$order3->addOrderItem($product2);
		$order3->addOrderItem($product3);

		$assoc->addOrder($order1);
		$assoc->addOrder($order2);
		$assoc->addOrder($order3);
		$result = $assoc->getResult($product1);

		$this->assertEquals(
			array(
				new AssociationRule\Result($product2, 50)
			),
			$result

		);
	}

	/**
	 * @test
	 */
	public function orderRatioDesc()
	{
		$assoc = new AssociationRule();
		$order1 = new AssociationRule\Order(1);
		$order2 = new AssociationRule\Order(2);
		$order3 = new AssociationRule\Order(3);
		$order4 = new AssociationRule\Order(4);

		$product1 = new AssociationRule\Product(10);
		$product2 = new AssociationRule\Product(11);
		$product3 = new AssociationRule\Product(12);
		$product4 = new AssociationRule\Product(13);

		$order1->addOrderItem($product1);
		$order1->addOrderItem($product2);
		$order1->addOrderItem($product3);
		$order1->addOrderItem($product4);

		$order2->addOrderItem($product1);
		$order2->addOrderItem($product2);

		$order3->addOrderItem($product2);
		$order3->addOrderItem($product3);
		$order3->addOrderItem($product4);

		$order4->addOrderItem($product1);
		$order4->addOrderItem($product3);
		$order4->addOrderItem($product2);

		$assoc->addOrder($order1);
		$assoc->addOrder($order2);
		$assoc->addOrder($order3);
		$assoc->addOrder($order4);

		$result = $assoc->getResult($product1);

		$this->assertEquals(
			array(
				new AssociationRule\Result($product2, 100),
				new AssociationRule\Result($product3, 66),
				new AssociationRule\Result($product4, 33)
			),
			$result

		);
	}

	/**
	 * @test
	 */
	public function massTest()
	{
		$iteration = 20;

		for ($x = 0; $x <= $iteration; $x++) {

			$validIds = $this->validIds();
			$this->randomMassTest($validIds);
		}
	}

	/**
	 * @test
	 * @dataProvider validIds
	 * @param $id
	 */
	public function validOrderId($id)
	{
		$order = new AssociationRule\Order($id);
		$assoc = new AssociationRule();
		$assoc->addOrder($order);
	}

	/**
	 * @test
	 * @dataProvider validIds
	 * @param $id
	 */
	public function validProductId($id)
	{
		$order = new AssociationRule\Order(10);
		$order->addOrderItem(new AssociationRule\Product($id));
		$assoc = new AssociationRule();
		$assoc->addOrder($order);
	}

	/**
	 * @test
	 * @dataProvider invalidIds
	 * @param $id
	 * @expectedException \InvalidArgumentException
	 */
	public function invalidOrderId($id)
	{
		$order = new AssociationRule\Order($id);
		$assoc = new AssociationRule();
		$assoc->addOrder($order);
	}

	/**
	 * @test
	 * @dataProvider invalidIds
	 * @param $id
	 * @expectedException \InvalidArgumentException
	 */
	public function invalidProductId($id)
	{
		$order = new AssociationRule\Order(10);
		$order->addOrderItem(new AssociationRule\Product($id));
		$assoc = new AssociationRule();
		$assoc->addOrder($order);
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
			[$fakerFactory->randomFloat(null, 1, 100000000)],
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
			[0b11001101],
			[0x5C3A3],
		);
	}

	/**
	 * @return array
	 */
	public function invalidIds()
	{
		return array(
			[''],
			[0]
		);
	}

	/**
	 * @param array $validIds
	 */
	protected function randomMassTest(array $validIds)
	{
		$assoc = new AssociationRule();
		foreach ($this->validIds() as $validId) {
			$order = new AssociationRule\Order($validId[0]);
			$subValidIds = array_merge($validIds, $this->validIds());
			foreach ($subValidIds as $subValidId) {
				$product = new AssociationRule\Product($subValidId[0]);
				$order->addOrderItem($product);
			}
			$assoc->addOrder($order);
		}

		foreach ($validIds as $validId) {
			$result = $assoc->getResult(new AssociationRule\Product($validId[0]));
			$this->assertNotEmpty($result);
		}
	}

	/**
	 * @test
	 */
	public function exportModel()
	{
		$assoc = new AssociationRule();
		$order1 = new AssociationRule\Order(1);
		$order2 = new AssociationRule\Order(2);
		$order3 = new AssociationRule\Order(3);
		$order4 = new AssociationRule\Order(4);

		$product1 = new AssociationRule\Product(10);
		$product2 = new AssociationRule\Product(11);
		$product3 = new AssociationRule\Product(12);
		$product4 = new AssociationRule\Product(13);

		$order1->addOrderItem($product1);
		$order1->addOrderItem($product2);
		$order1->addOrderItem($product3);
		$order1->addOrderItem($product4);

		$order2->addOrderItem($product1);
		$order2->addOrderItem($product2);

		$order3->addOrderItem($product2);
		$order3->addOrderItem($product3);
		$order3->addOrderItem($product4);

		$order4->addOrderItem($product1);
		$order4->addOrderItem($product3);
		$order4->addOrderItem($product2);

		$assoc->addOrder($order1);
		$assoc->addOrder($order2);
		$assoc->addOrder($order3);
		$assoc->addOrder($order4);

		$model = $assoc->exportModel();
		$result = $model->getResult($product1);

		var_dump($result);

		/** @var AssociationRule\Result $item */
		foreach ($result as $item) {
			$item->getAssociationPercent();
			$item->getId();
			$item->getProduct();
		}

		$this->assertEquals(
			array(
				new AssociationRule\Result($product2, 100),
				new AssociationRule\Result($product3, 66),
				new AssociationRule\Result($product4, 33)
			),
			$result
		);
	}

}
