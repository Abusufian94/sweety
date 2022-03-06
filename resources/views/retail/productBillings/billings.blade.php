@extends('layouts.'.$extend)
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">
        <h1>BILLING SECTION</h1>
        <br>

         <input type="hidden" id= "unit"/>
            <div class="pd-20 card-box mb-30">
                <form id="myform" method="post" enctype="multipart/form-data">
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-10">
                            <input type="text" onkeyup="getSuggestiveProduct(this.value)" id="search"
                                class="typeahead form-control" placeholder="Search Sweets">
                        </div>
                    </div>

                    <div class="form-group row">
                        <ul class="list-group" id="suggestion">

                        </ul>
                    </div>
                    <input type ="hidden" id ="total" value ="0"/>
                    <div class="card-box mb-30">
                        <table id="example1" class="table nowrap responsive">


                        </table>
                    </div>
                    <div class ="row">

                        <div class="col-md-4">

                        </div>
                        <div class="col-md-4">
                            <table class="table" id ="final_bill">

                            </table>
                        </div>

                    </div>
                    <div id="outputArea"></div>
                </form>

            </div>
        </div>
    </div>

    </div>
    </div>

    <style>
        .validator {
            border:solid 1px red;
            color:red;
        }
    </style>

      <script src="{{ asset('js/jquery-min.js') }}"></script>
    <script>
        async function getSuggestiveProduct(query) {
            var search = $("#search").val();
            if (search != '' && search != null) {
                var path = "{{ url('api/v1/admin/suggestive-product') }}";
                const token = JSON.parse(localStorage.getItem('loginUser'));
                const result = await $.ajax({
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token.token
                    },
                    url: path,
                    data: {
                        name: query
                    }
                });
                var html = ` <ul class="list-group" id="suggestion">`;
                $.each(result.data.data, function(index, value) {
                    html +=
                        `<a href="javascript:getproduct(${value.product_id})"><li class ='list'>
                            <img src ="${value.product_image_url}" height="50" width="50">
                            &nbsp;&nbsp;
                            <strong>${value.product_name}</strong>
                            [${value.quantity}]
                            </li></a>`
                });
                html += `</ul>`;
                $("#suggestion").html(html)
            }
        }
        var productIds = [];
        async function getproduct(id) {
            $("#suggestion").html(' ')
            $("#search").val('');
            if (productIds.indexOf(id) == -1) {
                productIds.push(id)
            }
            var path = "{{ url('api/v1/admin/suggestive-product') }}";
            const token = JSON.parse(localStorage.getItem('loginUser'));
            const result = await $.ajax({
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token.token
                },
                url: path,
                data: {
                    ids: productIds.toString()
                }
            });

            createHtml(result.data.data)

        }
        var billings = [];
        var payloads = [];
        async function totalPrice(val, price, product_id,unit, quantity) {
            let qty = $("#qty_"+product_id).val();
            let mesurment = $("#quantitytype_"+product_id).val().toUpperCase();
            var result = 0;
            switch(mesurment){
                // case "KG":
                // result = (Number(price) / 1000) * val;
                // break;
                case "GM":
                result = ((1000 / Number(price)) * Number(val));
                break;
                default:
                result = Number(val) * Number(price)
                break;
            }
            let totalCal = 0;
            $(".subTotal").each(function(index,item){
             let value = $(item).val();
             totalCal = totalCal + Number(value);
            });
            $("#subtotal_"+product_id).val(result);
            $("#grandtotal").text(totalCal.toFixed(2));
            if(mesurment === "GM") {
                quantity *= 1000;
            }

            if(Number(val) <= Number(quantity) && Number(val)>0) {
                await checkQuantity(product_id);
            var path = "{{ url('api/v1/admin/suggestive-product') }}";
            const token = JSON.parse(localStorage.getItem('loginUser'));
            const response = await $.ajax({
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token.token
                },
                url: path,
                data: {
                    ids: product_id.toString()
                }
            });
            var payload = {
                 product_id : response.data.data[0].product_id,
                 product_image: response.data.data[0].product_image_url,
                product_name: response.data.data[0].product_name,
                product_price: response.data.data[0].product_price,
                product_quantity: qty,
                total_price: result.toFixed(2),
                product_unit: mesurment,
            }
            index = billings.findIndex(el => el.product_id ===product_id);
            if(index == -1){
                billings.push(payload)
            }
            else {
                billings.splice(index,1);
                billings.push(payload)
            }
            let sum = 0;
            $.each(billings, function(index, item){
                sum = sum + Number(item.total_price);
                $("#total").val(sum);
            })
           let payloadIndex = payloads.findIndex(el => el.product_id == product_id)

            if(payloadIndex  == -1) {
                payloads.push({
                        product_id: response.data.data[0].product_id,
                        quantity: qty,
                        product_price: response.data.data[0].product_price,
                        price: result,
                        unit: mesurment
                     });

            } else  {
                payloads.splice(payloadIndex,1);
                payloads.push({
                        product_id: response.data.data[0].product_id,
                        quantity: qty,
                        product_price: response.data.data[0].product_price,
                        price: result,
                        unit: mesurment,
                     });
            }
            let grandTotal =  Number($("#total").val());
            $("#grandtotal").html(`${grandTotal.toFixed(2)}`);
            $("#total_" + product_id).html(`<i class="fa fa-inr"> ${result.toFixed(2)}</i>`);
            $("#qty_"+product_id).removeClass('validator');
            $("#span_"+product_id).hide();
            $("#bill").prop('disabled', false);

            } else {
                $("#span_"+product_id).addClass('validator').text("!! WRONG ENTRY !!");
                $("#qty_"+product_id).addClass('validator');
                $("#span_"+product_id).show();
                $("#qty_"+product_id).blur(function() {
                    // $(this).focus()

                });
                $("#bill").prop('disabled', true);
            }

        }

        async function remove(id) {

            let index = productIds.indexOf(id);
            productIds.splice(index, 1);
            var html = '';
            if (productIds.length > 0) {
                var path = "{{ url('api/v1/admin/suggestive-product') }}";
                const token = JSON.parse(localStorage.getItem('loginUser'));
                const result = await $.ajax({
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token.token
                    },
                    url: path,
                    data: {
                        ids: productIds.toString()
                    }
                });
                createHtml(result.data.data)
            } else {
                createHtml()
            }
        }

        function createHtml(data = null) {

            var html = ``;
            if (data != null) {
                 html = `<thead>
                    <tr>
                        <th class="all">SL</th>
                        <th class="all">Image</th>
                        <th class="all">Name</th>
                        <th class="all">Price</th>
                        <th class="all">Qty</th>
                        <th class="all">Unit</th>
                        <th class="all">Total</th>
                        <th class="all">Action</th>
                    </tr>
                </thead>
                <tbody id="demo">
                `
                ;

                var sum = 0;

                $.each(data, function(index, value) {

                    let calculateprice  = (value.product_unit == 'gm' || value.product_unit == 'GM') ? ((1000 / Number(value.product_price)) * 1): Number(value.product_price);
                    let quantity = (value.product_unit == 'gm') ? 100:1;
                    billings[index] = {
                        product_id : value.id,
                        product_image: value.product_image_url,
                        product_name: value.product_name,
                        product_price: value.product_price,
                        product_quantity: quantity,
                        total_price: calculateprice,
                        product_unit: value.product_unit.toUpperCase()
                    }
                     sum = sum + Number(calculateprice);
                     $("#total").val(sum);
                     payloads[index] = {
                        product_id: value.id,
                        unit: value.unit,
                        quantity: 1,
                        price: calculateprice,
                        product_price: value.product_price,
                     }

                    html += `
                <tr id='prod_${value.product_id}' class="product">
                <td>${index + 1}</td>
                <td><img src="${value.product_image_url}" alt="14 Product Photography Tips to Make You Look Like a Pro" jsaction="load:XAeZkd;" jsname="HiaYvf" class="n3VNCb" data-noaft="1" style="width: 100px; height: 100px; margin: 9.4px 0px;"></td>
                <td>${value.product_name}</td>
                <td><i class="fa fa-inr">${value.product_price}</i> </td>

                <td><input type="hidden" class='subTotal' id="subtotal_${value.product_id}" value="${value.product_price}"/><input type="number" id ="qty_${value.product_id}" value="${quantity}"  style="height: 34px" onkeyup="totalPrice(this.value,${value.product_price},${value.product_id},'${value.product_unit}',${value.quantity})" onchange="totalPrice(this.value,${value.product_price},${value.product_id},'${value.product_unit}',${value.quantity})"/><br/><small id='span_${value.product_id}'></small</td>

                <td><select id="quantitytype_${value.product_id}" onchange = 'unitCalculation(this.value,${value.product_id},${value.product_price},${quantity})'>${(value.product_unit == 'kg'||value.product_unit == 'gm')?`<option ${(value.product_unit == 'kg') ?'selected':''} value='KG'>KG</option><option ${(value.product_unit == 'gm') ?'selected':''} value ='GM' >GM</option>`:`<option>Pcs</option>`}</select></td>
                <td id="total_${value.product_id}" class ="calculate" ><i class="fa fa-inr"> ${calculateprice.toFixed(2)} </i></td>
                <td><button type="button" class="btn btn-danger"  onclick='remove(${value.product_id})'><i class="fa fa-trash"></i></button></td>
                </tr>
                `;

                });
                let total = Number($("#total").val());
               // $("#grandtotal").html(`<i class="fa fa-inr">${Number(total).toFixed(2)}</i>`)
                html += `<tr>
                <td colspan ='6'>Total</td>
                <td colspan ='4' ><i class="fa fa-inr" id ='grandtotal'>${total.toFixed(2)}</i></td>
                <td></td>
                </tr><tr><td colspan ='4'><button type="button" id ="bill" class="btn btn-success" onclick ="saveBill()">Save<i class="fa-solid fa-floppy-disk"></i></button></td><td colspan ='6'></tr></tbody>
                `;

            }

            $("#example1").html(html);

        }
        function unitCalculation(val,product_id,price,quantity) {
            let qty = $("#qty_"+product_id).val();
            let mesurment = $("#quantitytype_"+product_id).val().toUpperCase();
            let result;

         if(mesurment == "GM"){
         result =  ((Number(price)) * qty)/1000;
            $("#total_"+product_id).html(`<i class="fa fa-inr"> ${result.toFixed(2)}</i>`);
            $("#subtotal_"+product_id).val(result);
         }
         else if(mesurment == "KG"){
            result = (Number(price)) * qty;
            $("#total_"+product_id).html(`<i class="fa fa-inr"> ${result.toFixed(2)}</i>`);
            $("#subtotal_"+product_id).val(result);
         }
         else {
             result = (Number(qty) * Number(price)) / 1000;
            $("#subtotal_"+product_id).val(result);
            $("#total_"+product_id).html(`<i class="fa fa-inr"> ${result.toFixed(2)}</i>`);
         }
         //let subtotal = $("#subtotal_"+product_id).val();
         let totalCal = 0;
         $(".subTotal").each(function(index,item){
             let value = $(item).val();
             totalCal = totalCal + Number(value);
         });
         $("#total").val(totalCal.toFixed(2));
         $("#grandtotal").text(totalCal.toFixed(2));
         let payloadIndex = payloads.findIndex(el => el.product_id == product_id)

         if(payloadIndex  == -1) {
                payloads.push({
                        product_id: product_id,
                        quantity: qty,
                        product_price: price,
                        price: result,
                        unit: mesurment
                     });

            } else  {
                payloads.splice(payloadIndex,1);
                payloads.push({
                        product_id: product_id,
                        quantity: qty,
                        product_price: price,
                        price: result,
                        unit: mesurment,
                     });
            }


        }
        /** save bill details **/
        async function saveBill() {
            let products = JSON.stringify(payloads)
            let totalPrice = Number($("#total").val());
            var path = "{{ url('api/v1/admin/save-billings') }}";
            const token = JSON.parse(localStorage.getItem('loginUser'));
            const response = await $.ajax({
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token.token
                },
                url: path,
                type:"POST",
                dataType: 'text',
                dataType: 'json',

                data: {
                    totalPrice: totalPrice,
                    paymentMethod: 'cash',
                    products: products
                }
            });
            if(response.stat == true) {
                window.open(response.data, '_blank');
            }
        }
    </script>
    <script>
        async function checkQuantity(productId){
            let response = await apiCall("{{ url('api/v1/check/retail-quantity') }}","POST",{product_id: productId});
            if(response.stat == false){
              $("#qty_"+productId).css({
                "border-style": "solid",
                "border-color": "red",
              });
              $("#span_"+productId).focus();
              $("#span_"+productId).css({"color":"red"}).text(response.message);
              $("#span_"+productId).show();
            //  document.getElementById("bill").disabled = false;

            } else {
                $("#span_"+productId).hide();
            }
        }
    </script>
    <script>
        $(document).ready(function() {

            $("#product_name").focus();
            var x = localStorage.getItem("loginUser");

            x = JSON.parse(x);
            if (x == null) {
                window.location.replace("{{ url('/') }}");
            }
            if (!x.token && x.role != 1) {
                localStorage.setItem("unAuthorized", " Sorry, You are not authorized");
                window.location.replace("{{ url('/') }}");
            } else {
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: localStorage.getItem("loggedInMessage"),
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            // loadattribute();
            $("#myform").validate({
                rules: {
                    product_unit: {
                        required: true
                    },
                    product_price: {
                        required: true
                    },
                    product_name: {
                        required: true
                    },
                    product_quantity: {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    const obj = $(form).serializeArray();
                    const token = JSON.parse(localStorage.getItem('loginUser'));
                    const user_id = token.id;


                    var raw_name = $("#raw_name").val();
                    var unit = $('[name="unit"]').val();
                    var stock = $('[name="stock"]').val();
                    var price = $('[name="price"]').val();

                    var form_data = new FormData();
                    form_data.append("raw_name", raw_name);
                    form_data.append("unit", unit);
                    form_data.append("stock", stock);
                    form_data.append("price", price);
                    form_data.append("user_id", user_id);


                    // const obj = $(form).serializeArray();
                    // const token = JSON.parse(localStorage.getItem('loginUser'));
                    // const user_id = token.id;


                    var product_name = $("#product_name").val();
                    var product_unit = $('[name="product_unit"]').val();
                    var product_quantity = $('[name="product_quantity"]').val();
                    var product_price = $('[name="product_price"]').val();
                    var product_image = $('[name="product_image"]').val();

                    var form_data = new FormData(form);
                    form_data.append("product_name", product_name);
                    form_data.append("product_unit", product_unit);
                    form_data.append("product_quantity", product_quantity);
                    form_data.append("product_price", product_price);
                    form_data.append("user_id", user_id);

                    var product_price = $('[name="product_price"]').val();
                    $.ajax({
                        url: "{{ url('api/v1/pro/create') }}",
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token.token
                        },
                        type: form.method,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json", // what to expect back from the server
                        data: form_data,
                        success: function(response) {
                            $(form)[0].reset()
                            window.location = "{{ route('product.home') }}"
                        }
                    });

                }
            })



        });

        // async function loadattribute() {
        //     const token = JSON.parse(localStorage.getItem('loginUser'));
        //     const response = await $.ajax({
        //         url: "{{ route('rawstock.rawlist') }}",
        //         type: 'get',
        //         headers: {
        //             'Accept': 'application/json',
        //             'Authorization': 'Bearer ' + token.token
        //         },
        //         contentType: false,
        //         cache: false,
        //         processData: false,
        //         dataType: "json", // what to expect back from the server
        //         success: function(response) {
        //             $('#raw').empty();

        //             const data = response.data;
        //             let html = '';
        //             for (var i = 0; i < data.length; i++) {
        //                 console.log(data[i].raw_id)
        //                 html +=
        //                     `<option value="${data[i].raw_id}">${data[i].raw_name}</option> `;

        //             }

        //             $('#raw').append(
        //                 `<option value="" disabled>Select Raw Material</option>${html}`);

        //         }
        //     });

        // }


        // function get_raw(select)
        // {
        //    // var result = [];
        //     var options = select && select.options;
        //     var html = '';

        //     $.each(options,function(index,item){

        //         if(item.selected)
        //         {
        //           let names = $(item).text();
        //           html +=  `<label>${names}</label>
    //                         <input type ="hidden" name ="raw_id[]" value ="${item.value}"/>
    //                         <div class="form-group row">
    //                             <label class="col-sm-12 col-md-2 col-form-label">Raw Unit</label>
    //                                 <div class="col-sm-12 col-md-10">
    //                                     <select name='unit[]'  class="form-control" required placeholder="Select">
    //                                         <option value="" disabled>Select Unit</option>
    //                                         <option value="kg">KG</option>
    //                                         <option value="mg">Mg</option>
    //                                         <option value="li">Litre</option>
    //                                         <option value="ml">Mili Liter</option>
    //                                         <option value="pcs">Pcs</option>
    //                                     </select>
    //                                 </div>

    //                         </div>
    //                         <div class="form-group row">
    //                             <label class="col-sm-12 col-md-2 col-form-label">Raw Stock</label>
    //                                 <div class="col-sm-12 col-md-10">
    //                             <input class='form-control' type='text' placeholder='Stock' name='stock[]' />
    //                             </div>
    //                         </div>`;

        //         }
        //     });
        //     $('#outputArea').append(html);
        // }
    </script>
@endsection
