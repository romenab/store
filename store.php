<?php
$products = json_decode(file_get_contents('products.json'));
function showProducts($products)
{
    $nameAndPrice = "Products and prices: " . PHP_EOL;
    foreach ($products->products as $product) {
        $priceEuro = number_format($product->price / 100, 2);
        $nameAndPrice .= $product->name . " €" . $priceEuro . PHP_EOL;
    }
    echo $nameAndPrice . PHP_EOL;
}

showProducts($products);
$cart = [];
while (true) {
    $yesNo = strtolower(readline("Do you want to add a new item to cart? "));
    if ($yesNo == "yes" || $yesNo == "y") {
        addToCart($products, $cart);
        showCart($cart, $products);
        return false;
    } elseif ($yesNo == "no" || $yesNo == "n") {
        showCart($cart, $products);
        return false;
    } else {
        echo "Invalid input." . PHP_EOL;
    }
}

function addToCart($products, &$cart)
{
    while (true) {
        $userNewItem = strtolower(readline("Which product would you like to add to cart? "));
        $productFound = false;
        foreach ($products->products as $product) {
            if (strtolower($product->name) === $userNewItem) {
                $productFound = true;
                while (true) {
                    $userAmount = readline("What amount? ");
                    echo PHP_EOL;
                    if (is_numeric($userAmount) && $userAmount > 0) {
                        $isInCart = false;
                        foreach ($cart as &$cartItem) {
                            if ($cartItem["name"] == $product->name) {
                                $isInCart = true;
                                $cartItem["amount"] += $userAmount;
                                break;
                            }
                        }
                        if (!$isInCart) {
                            $cart[] = ["name" => $product->name, "amount" => $userAmount, "price" => $product->price];
                        }
                        return;
                    } else {
                        echo "Invalid number!" . PHP_EOL;
                    }
                }
            }
        }
        if (!$productFound) {
            echo "Invalid input." . PHP_EOL;
        }
    }
}

function showCart($cart, $products)
{
    while (true) {
        if (empty($cart)) {
            echo "Your cart is empty." . PHP_EOL;
            exit;
        } else {
            echo "Your cart:" . PHP_EOL;
            $totalPrice = 0;
            foreach ($cart as $item) {
                $itemPrice = number_format($item["price"] / 100, 2);
                echo $item["amount"] . " unit(s) of " . $item["name"] . " for €" . $itemPrice . " per item." . PHP_EOL;
                $total = $itemPrice * $item["amount"];
                $totalPrice += $total;
            }
            echo "Total Price: €" . $totalPrice . PHP_EOL;
            echo PHP_EOL;
            while (true) {
                $addMore = strtolower(readline("Do you want to add more items to cart? "));
                if ($addMore == "yes" || $addMore == "y") {
                    showProducts($products);
                    addToCart($products, $cart);
                    showCart($cart, $products);
                } elseif ($addMore == "no" || $addMore == "n") {
                    echo "Your total is €" . $totalPrice . PHP_EOL;
                    echo "Thank you for your purchase!";
                    exit;
                } else {
                    echo "Invalid input." . PHP_EOL;
                }
            }
        }
    }
}

