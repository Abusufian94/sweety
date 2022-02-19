@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">

            <div class="pd-20 card-box mb-30">
                <form id="myform" method="post" enctype="multipart/form-data">
                <div class="form-group row">
                    <div class="col-sm-12 col-md-10">
                        <input type="text"  onkeyup="getSuggestiveProduct(this.value)" id="search" class="typeahead form-control" placeholder="Search">
                    </div>

                </div>

                <div class="form-group row">
                    <ul class="list-group" id="suggestion">

                    </ul>
                </div>

                <div class="card-box mb-30">
                    <table id="example1" class="table nowrap responsive">


                    </table>
                </div>



                {{-- <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Select Raw Material<small
                            style="color:red">*</small></label>
                    <div class="col-sm-12 col-md-10" style="width:100%;">

                        <select  data-width="100%" multiple name="raw" required="required"
                            id="raw" class="form-control"   onchange="get_raw(this)">
                            <option value="" disabled>Select Raw Material</option>
                        </select>
                    </div>
                </div> --}}

                <div id="outputArea"></div>




                {{-- <div class="form-group row"> --}}
                    {{-- <label class="col-sm-12 col-md-2 col-form-label">Confirm Password</label> --}}
                    {{-- <div class="col-sm-12 col-md-10">

                    </div>
                </div> --}}
            </form>

            </div>
        </div>
    </div>

    </div>
    </div>
    <style>
        .ui-state-active h4,
        .ui-state-active h4:visited {
            color: #26004d ;
        }

        .ui-menu-item{
            height: 80px;
            border: 1px solid #ececf9;
        }
        .ui-widget-content .ui-state-active {
            background-color: white !important;
            border: none !important;
        }
        .list_item_container {
            width:740px;
            height: 80px;
            float: left;
            margin-left: 20px;
        }
        .ui-widget-content .ui-state-active .list_item_container {
            background-color: #f5f5f5;
        }

        .image {
            width: 15%;
            float: left;
            padding: 10px;
        }
        .image img{
            width: 80px;
            height : 60px;
        }
        .label{
            width: 85%;
            float:right;
            white-space: nowrap;
            overflow: hidden;
            color: rgb(124,77,255);
            text-align: left;
        }
        input:focus{
            background-color: #f5f5f5;
        }

        </style>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script>

        async function getSuggestiveProduct(query) {
          var search  = $("#search").val();
          if(search != '' && search!= null) {
            var path = "{{url('api/v1/admin/suggestive-product')}}";
            const token = JSON.parse(localStorage.getItem('loginUser'));
            const result  = await $.ajax({
            headers: {
            'Accept':'application/json',
            'Authorization':'Bearer '+token.token
            },
             url:path,
             data: {name:query}});
             var html = ` <ul class="list-group" id="suggestion">`;
             $.each( result.data.data, function( index, value ){
                      html +=`<a href="javascript:getproduct(${value.product_id})"><li class ='list'><img src ="${value.product_image_url}" height="30" width="30"><strong>${value.product_name}</strong><b>${value.product_quantity}</b></li></a>`
                });
                html += `</ul>`;
             $("#suggestion").html(html)
          }
    }
   var productIds = [];
   async function getproduct(id) {
       if(productIds.indexOf(id) == -1) {
        productIds.push(id)
       }
       var path = "{{url('api/v1/admin/suggestive-product')}}";
            const token = JSON.parse(localStorage.getItem('loginUser'));
            const result  = await $.ajax({
            headers: {
            'Accept':'application/json',
            'Authorization':'Bearer '+token.token
            },
             url:path,
             data: {ids:productIds.toString()}});
    var html = `<thead>
                        <tr>
                            <th class="all">SL</th>
                            <th class="all">Image</th>
                            <th class="all">Name</th>
                            <th class="all">Price</th>
                            <th class="all">Qty</th>
                            <th class="all">Total</th>
                            <th class="all">Action</th>
                        </tr>
                    </thead>
                    <tbody id="demo">`;
     $.each(result.data.data, function( index, value ){
       html += `
                <tr id='prod_${value.product_id}'>
                <td>${index + 1}</td>
                <td><img src="${value.product_image_url}" alt="14 Product Photography Tips to Make You Look Like a Pro" jsaction="load:XAeZkd;" jsname="HiaYvf" class="n3VNCb" data-noaft="1" style="width: 54.4px; height: 34px; margin: 9.4px 0px;"></td>
                <td>${value.product_name}</td>
                <td> <i class="fa fa-inr">${value.product_price}</i> </td>
                <td><input type="number" value="1"  style="height: 34px" onchange="totalPrice(this.value,${value.product_price},${value.product_id})"/></td>
                <td id="total_${value.product_id}"><i class="fa fa-inr"></i> ${value.product_price}</td>
                <td><button type="button" class="btn btn-danger" onclick='remove(${value.product_id})'><i class="fa fa-trash"></i></button></td>
                </tr>`;
    });
    html +=`</tbody>`;
    $("#example1").html(html);
   }
   function totalPrice(val,price,product_id) {

    let result = Number(val) * Number(price)
    $("#total_"+product_id).html(`<i class="fa fa-inr"> ${result}</i>`);
   }
   function remove(id) {
       console.log(productIds.length)
    if(productIds.length > 1) {
        let index  = productIds.indexOf(id);
        productIds.splice(index, 1);
        $("#prod_"+id).remove();
    } else {
        $("#example1").remove();
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
