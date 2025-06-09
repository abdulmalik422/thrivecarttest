<?php


$catalogue = [
    'R01' => [
    	'product' => 'Red Widget', 
    	'code' => 'R01', 
    	'price' => 32.95
    ],
    'G01' => [
    	'product' => 'Green Widget', 
    	'code' => 'G01', 
    	'price' => 24.95
    ],
    'B01' => [
    	'product' => 'Blue Widget', 
    	'code' => 'B01', 
    	'price' => 7.95
    ],
];

$deliveryCharges = [
    [
    	'orderAmount' => 50.00, 
    	'cost' => 4.95
    ],
    [
    	'orderAmount' => 90.00, 
    	'cost' => 2.95
    ]
];


$specialOffers = [
    'red_widget_offer' => true,
];

$basket = [];

function addBasket(array &$basket, string $productCode, array $catalogue)
{
    if (!isset($catalogue[$productCode])) {
        throw new Exception("Product with code '{$productCode}' doesn\'t exist.");
    }
    $basket[] = $productCode;
}

function calculateDelivery(float $currentTotal, array $deliveryCharges)
{
    foreach ($deliveryCharges as $rule) {
        if ($currentTotal < $rule['orderAmount']) {
            return $rule['cost'];
        }
    }
    return 0; // Free delivery for above $90
}

function calcTotal(array $basket, array $catalogue, array $deliveryCharges, array $specialOffers){
	$subtotal = 0;
    $itemCounts = [];
    foreach ($basket as $code) {
        if (!isset($itemCounts[$code])) {
            $itemCounts[$code] = 0;
        }
        $itemCounts[$code]++;
    }

    foreach ($itemCounts as $productCode => $count) {
        $product = $catalogue[$productCode];
        $price = $product['price'];

        // Apply offers
        if (isset($specialOffers['red_widget_offer']) && $productCode === 'R01') {
		    $discountPrice = floor($count / 2);
		    $fullPrice = $count - $discountPrice;
		    $subtotal += ($fullPrice * $price) + ($discountPrice * $price / 2);
		}
        else {
            $subtotal += $count * $price;
        }
    }

    $deliveryCharge = calculateDelivery($subtotal, $deliveryCharges);

    return round($subtotal + $deliveryCharge, 2);
}


// Secnario 1: Product B01, G01		Total: $37.85
$basket = [];
addBasket($basket,'B01',$catalogue);
addBasket($basket,'G01',$catalogue);
echo "<br> // Test Secnario 1: Product B01, G01  - Total: ";
echo calcTotal($basket,$catalogue,$deliveryCharges,$specialOffers);


// Secnario 2: Product R01, R01		Total: $54.37
$basket = [];
addBasket($basket,'R01',$catalogue);
addBasket($basket,'R01',$catalogue);
echo "<br> // Test Secnario 2: Product R01, R01  - Total: ";
echo calcTotal($basket,$catalogue,$deliveryCharges,$specialOffers);



// Secnario 3: Product R01, G01		Total: $60.85
$basket = [];
addBasket($basket,'R01',$catalogue);
addBasket($basket,'G01',$catalogue);
echo "<br> // Test Secnario 3: Product R01, G01  - Total: ";
echo calcTotal($basket,$catalogue,$deliveryCharges,$specialOffers);



// Secnario 4: Product B01, B01, R01, R01, R01		Total: $98.27
$basket = [];
addBasket($basket,'B01',$catalogue);
addBasket($basket,'B01',$catalogue);
addBasket($basket,'R01',$catalogue);
addBasket($basket,'R01',$catalogue);
addBasket($basket,'R01',$catalogue);
echo "<br> // Test Secnario 4: Product B01, B01, R01, R01, R01  - Total: ";
echo calcTotal($basket,$catalogue,$deliveryCharges,$specialOffers);