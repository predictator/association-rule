# Association rule

## Usage

```php
$assoc = new AssociationRule();

$product1 = new AssociationRule\Product(10);
$product2 = new AssociationRule\Product(11);
$product3 = new AssociationRule\Product(12);

$order1 = new AssociationRule\Order(1);
$order1->addOrderItem($product1);
$order1->addOrderItem($product2);
$assoc->addOrder($order1);

$order2 = new AssociationRule\Order(2);
$order2->addOrderItem($product1);
$assoc->addOrder($order2);

$order3 = new AssociationRule\Order(3);
$order3->addOrderItem($product2);
$order3->addOrderItem($product3);
$assoc->addOrder($order3);

$result = $assoc->getResult(new AssociationRule\Product(10));

```

### Export model
```php
$model = $assoc->exportModel();
$result = $model->getResult(new AssociationRule\Product(10));

```

### Process result
```php
/** @var AssociationRule\Result $item */
foreach ($result as $item) {
	$item->getAssociationPercent(); 
	$item->getId();
	$item->getProduct();
}
```
## Test
[![Build Status](https://travis-ci.org/predictator/association-rule.svg?branch=master)](https://travis-ci.org/predictator/association-rule)


predictator.eu