<?php
use App\Product;
function getProductName($id) {
  $productName = Product::find($id)->product_name;
  return $productName;
}
?>
