# Acme Widget PHP Shopping Basket
This PHP script for a simple shopping basket, including how it calculates the total cost based on product prices, delivery rules, and special offers.

**It has following features and functionality:**
- Product Catalogue: Defines products by a unique code, name, and price.
- Dynamic Delivery Charges:
  1. Orders under $50 cost $4.95 for delivery.
  2. Orders under $90 cost $2.95 for delivery.
  3. Free Delivery Orders of $90 or more.
- Special Offer: "Buy one red widget, get the second half price." (Applied to 'R01').
- It has two main functions:
  1. addBasket(): Adds a product to the basket by its product code.
  2. calcTotal(): Calculates the total cost, considering offers and delivery.
 
**Usage Examples:**
To run the code, save it as a .php file (e.g., basket.php) and execute it using a PHP interpreter:

php basket.php
