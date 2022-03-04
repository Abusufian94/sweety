<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <title>Invoice</title>
</head>
<body>
  <h1>Product Billing invoice</h1>
  <div>
    <table>
        <tr>
          <td>Invoice No</td>
          <td>{{$Invoice_No}}</td>
        </tr>
        <tr>
            <td>Retailer Name</td>
            <td>{{$Retailer_name}}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td>SL</td>
            <td>Name</td>
            <td>Quantity</td>
            <td>Price</td>
        </tr>
        @foreach ($sold_product as $key=>$product)
        <tr>
        <td>{{$key + 1}}</td>
        <td>{{getProductName($product['product_id'])}}</td>
        <td>{{$product['quantity']}}</td>
        <td>{{number_format($product['price'], 2)}}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2">Grand Total</td>
            <td colspan="2">{{number_format($total_price, 2)}}</td>
        </tr>
    </table>

  </div>
</body>
</body>
</html>
