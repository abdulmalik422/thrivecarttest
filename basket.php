<?php

class Basket
{
    private array $catalogue;
    private array $deliveryCharges;
    private array $specialOffers;
    private array $basket = [];

    public function __construct(
        array $catalogue, 
        array $deliveryCharges, 
        array $specialOffers
    )
    {
        $this->catalogue = $catalogue;
        $this->deliveryCharges = $deliveryCharges;
        $this->specialOffers = $specialOffers;
    }

    public function addBasket(string $productCode)
    {
        if (!isset($this->catalogue[$productCode])) {
            throw new Exception("Product with code '{$productCode}' doesn't exist.");
        }
        $this->basket[] = $productCode;
    }

    public function calcTotal()
    {
        $subtotal = 0;
        $itemCounts = [];

        foreach ($this->basket as $code) {
            if (!isset($itemCounts[$code])) {
                $itemCounts[$code] = 0;
            }
            $itemCounts[$code]++;
        }

        foreach ($itemCounts as $productCode => $count) {
            $product = $this->catalogue[$productCode];
            $price = $product['price'];

            // Apply special offers
            if (isset($this->specialOffers['red_widget_offer']) && $productCode === 'R01') {
                $discountPrice = floor($count / 2);
                $fullPrice = $count - $discountPrice;
                $subtotal += ($fullPrice * $price) + ($discountPrice * $price / 2);
            } else {
                $subtotal += $count * $price;
            }
        }

        $deliveryCharge = $this->calculateDelivery($subtotal);

        return round($subtotal + $deliveryCharge, 2);
    }

    private function calculateDelivery(float $currentTotal)
    {
        foreach ($this->deliveryCharges as $rule) {
            if ($currentTotal < $rule['orderAmount']) {
                return $rule['cost'];
            }
        }
        return 0;
    }

}


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


// Secnario 1: Product B01, G01     Total: $37.85
$basket1 = new Basket($catalogue, $deliveryCharges, $specialOffers);
$basket1->addBasket('B01');
$basket1->addBasket('G01');
echo "<br> // Test Secnario 1: Product B01, G01  - Total: ";
echo $basket1->calcTotal();


// Secnario 2: Product R01, R01     Total: $54.37
$basket2 = new Basket($catalogue, $deliveryCharges, $specialOffers);
$basket2->addBasket('R01');
$basket2->addBasket('R01');
echo "<br> // Test Secnario 2: Product R01, R01  - Total: ";
echo $basket2->calcTotal();



// Secnario 3: Product R01, G01     Total: $60.85
$basket3 = new Basket($catalogue, $deliveryCharges, $specialOffers);
$basket3->addBasket('R01');
$basket3->addBasket('G01');
echo "<br> // Test Secnario 3: Product R01, G01  - Total: ";
echo $basket3->calcTotal();



// Secnario 4: Product B01, B01, R01, R01, R01      Total: $98.27
$basket4 = new Basket($catalogue, $deliveryCharges, $specialOffers);
$basket4->addBasket('B01');
$basket4->addBasket('B01');
$basket4->addBasket('R01');
$basket4->addBasket('R01');
$basket4->addBasket('R01');
echo "<br> // Test Secnario 4: Product B01, B01, R01, R01, R01  - Total: ";
echo $basket4->calcTotal();