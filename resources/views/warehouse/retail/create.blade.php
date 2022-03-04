@extends('layouts.'.$extend)
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">

            <div class="pd-20 card-box mb-30">
                <form id="myform" method="post" enctype="multipart/form-data">
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Product Unit<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10" style="width:100%;">

                            <select name="product_id" id="product_id" class="form-control" required="required">
                                <option value="" disabled selected>Select Product</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Product Unit<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10" style="width:100%;">

                            <select name="unity" id="unity" disabled class="form-control" required="required">
                                <option value="">Select Unit</option>
                                <option value="kg">KG</option>
                                <option value="mg">Mg</option>
                                <option value="li">Litre</option>
                                <option value="ml">Mili Liter</option>
                                <option value="pcs">Pcs</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Avaliable Quantity<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" placeholder="Quantity" name="aval_quantity"
                                id="aval_quantity" required="required" disabled />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Select Store<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10" style="width:100%;">

                            <select name="retail_id" id="retail_id" class="form-control" required="required">
                                <option value="" disabled selected>Select Store</option>
                            </select>
                        </div>
                    </div>
                          <div class="form-group row">
                             <span class="productTab"></span>
                          </div>

                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Product Quantity<small
                                style="color:red">*</small></label>
                       
                           
                           

                         <div class="col-sm-12 col-md-10">
                        
                            <span class="warningMsg"></span>
                            <input class="form-control" type="number" placeholder="Quantity" name="quantity" min="1" 
                                id="curquantity" required="required" / style="border:3px solid #555">
                            <span id="curquantityWrmsg"></span>
                        </div>
                    </div>
                   
                    <div class="form-group row">
                        {{-- <label class="col-sm-12 col-md-2 col-form-label">Confirm Password</label> --}}
                        <div class="col-sm-12 col-md-10">
                            <input class="btn btn-primary" type="submit" value="Submit" />
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    </div>
    </div>
    <script src="{{ asset('js/jquery-min.js') }}"></script>
    <script>
        $(document).ready(function() {

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
        var currentQty='';
        $(document).ready(function() {
             loadRetailUser();
             loadProduct();
            $("#myform").validate({
                rules: {
                    unity: {
                        required: true
                    },
                    retail_id: {
                        required: true
                    },
                    product_id: {
                        required: true
                    },
                    quantity: {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    const obj = $(form).serializeArray();
                    const token = JSON.parse(localStorage.getItem('loginUser'));
                    const user_id = token.id;
                    let unity = $("#unity").val();

                    var form_data = new FormData(form);
                    form_data.append("user_id", user_id);
                    form_data.append("unity", unity);
                    form_data.append("status", 0);


                    $.ajax({
                        url: "{{ url('api/v1/retail-assign-log') }}",
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
                            window.location = "{{ route('warehouse.retail.list') }}"
                        }
                    });

                }
            })



        });

        async function loadRetailUser() {
            const token = JSON.parse(localStorage.getItem('loginUser'));
            const response = await $.ajax({
                url: "{{ url('api/v1/retail-users') }}" ,
                type: 'get',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token.token
                },
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json", // what to expect back from the server
                success: function(response) {
                    $('#retail_id').empty();

                    const data = response.data;
                    let html = '';
                    for (var i = 0; i < data.length; i++) {
                        html +=
                            `<option value="${data[i].retail_id}">${data[i].retail_name}</option> `;

                    }

                    $('#retail_id').append(
                        `<option value="">Select Store</option>${html}`);

                }
            });

        }

        async function loadProduct() {
            const token = JSON.parse(localStorage.getItem('loginUser'));
            const response = await $.ajax({
                url: "{{ url('api/v1/pro/all') }}" ,
                type: 'get',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token.token
                },
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json", // what to expect back from the server
                success: function(response) {
                    $('#product_id').empty();

                    const data = response.data;
                    let html = '';
                    for (var i = 0; i < data.length; i++) {
                        html +=
                            `<option value="${data[i].id}" data-unit="${data[i].product_unit}" data-quantity="${data[i].product_quantity}">${data[i].product_name}</option> `;

                    }

                    $('#product_id').append(
                        `<option value="">Select Product</option>${html}`);

                }
            });

        }

        $('#product_id').change(function() {

            let unit = $(':selected', $(this)).data('unit');
            let quantity = $(':selected', $(this)).data('quantity');
            $("#unity").val(unit).attr("selected","selected");
            $("#aval_quantity").val(quantity);
            $("#quantity").attr('max', quantity);
                let product_id =  $('#product_id').val();
                alert(product_id)
             const token = JSON.parse(localStorage.getItem('loginUser'));
             $.ajaxSetup({
                headers: {
                          'Authorization': 'Bearer ' + token.token
                         }
            });
        $.ajax({
            url: "{{ url('api/v1/assigned-pending-total-stock?product_id=') }}"+product_id,
            headers: {
                    'Accept':'application/json',
                    'Authorization':'Bearer '+token.token
                     },
            type: "GET",
            dataType: "json",
          
            success: function (data) {
             

              let elArr=[];
                let qtyCount =0;
              $.each(data.data, function( index, value ) {
                    qtyCount+=parseInt(value.quantity);
              let  selectVal = `<tr>
                                    <td>${value.retail_name}</td>
                                    <td>${value.quantity}</td>
                                    <td>${value.unity}</td>
                                </tr>`;
               elArr.push(selectVal);
              
              
              });
                    let availableQty = $("#aval_quantity").val();
                   
                         currentQty = parseInt(availableQty)-parseInt(qtyCount);
                        if(elArr.length>0)
                        {
                        
              $('.warningMsg').html(`<div class="col-sm-8 col-md-6"><table border="border:3px solid #555"><tr><th>Retail Name </th><th>Qty</th><th>Unit</th></tr><tbody>${elArr.join('')}</tbody></table></div><br><span><b style="color:red">${currentQty} এর  অতিক্রম  পরিমাণ দেয়া যাবেনা ।</b></span>`);
                        }
                        else
                        {
                              $('.warningMsg').html(`<b> ${currentQty}এর  অতিক্রম  পরিমাণ দেয়া যাবেনা ।</b>`).css("color", "blue");
                        }
              

            

            },
            error: function (xhr, status, error) {
               alert(error);
               console.log(error);
                    }
                   

        });
        });


      $(document).ready(function(){
  $("#curquantity").keyup(function(){
    let curVal = $("#curquantity").val();
    if(curVal>currentQty)
    {
       $('#curquantityWrmsg').html(`<div style="background-color:yellow;"><h5>আপনি পরিমান  অতিক্রম করছেন 👿</h5><div>`).css("background-color","red");
        $(':input[type="submit"]').prop('disabled', true);
    }
    else 
    {
         $('#curquantityWrmsg').html(' ');
          $(':input[type="submit"]').prop('disabled', false);
    }
  });
});
    </script>

@endsection
