@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">
            <input type="hidden" id="pid" value="{{ $id }}" />

            <div class="pd-20 card-box mb-30">

                <form id="myform" method="POST" enctype="multipart/form-data">
                    <div class="form-group row">
                        <input class="form-control" type="hidden" name="id" id="id"
                                required />
                        <label class="col-sm-12 col-md-2 col-form-label">Product Name<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" name="product_name" id="product_name"
                                placeholder="Name" required />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Product Unit<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10" style="width:100%;">

                            <select class="selectpicker"  width="100%"
                                name="product_unit" id="product_unit" class="form-control" required >
                                <option value="" disabled>Select Unit</option>
                                <option value="kg">KG</option>
                                <option value="mg">Mg</option>
                                <option value="li">Litre</option>
                                <option value="ml">Mili Liter</option>
                                <option value="pcs">Pcs</option>
                            </select>
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Select Raw Material<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10" style="width:100%;">

                            <select  data-width="100%" multiple name="raw"
                                id="raw" class="form-control"   onchange="get_raw(this)">
                                <option value="" disabled>Select Raw Material</option>
                            </select>
                            <label><a href="#exampleModal" data-toggle="modal" data-target="#exampleModal">View Selected</a></label>

                        </div>
                    </div> --}}

                    <div id="outputArea"></div>


                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Product Quantity<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" placeholder="Quantity" name="product_quantity"
                                id="product_quantity" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Product Price<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" name="product_price" id="product_price" placeholder="Price" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Product Image</label> 
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="file" name="product_image" id="product_image"  />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div id="image"></div>
                    </div>

                    <div class="form-group row">
                        {{-- <label class="col-sm-12 col-md-2 col-form-label">Confirm Password</label> --}}
                        <div class="col-sm-12 col-md-10">
                            <input class="btn btn-primary" type="submit" value="Submit">
                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>

    </div>
    </div>
        <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Consumption List</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table">
            <thead>
              <tr>
                  <td>Name</td>
                  <td>unit </td>
                  <td>Quantity </td>
              </tr>
            </thead>
            <tbody id="attr_table">
             
            </tbody>

          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
         
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
     var arr=[];
        $(document).ready(function() {
           
          // loadattribute();
            //loadConsumption();
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
                    var form_data = new FormData(form);
                    form_data.append("user_id", user_id);


                    $.ajax({
                        url: "{{ url('api/v1/pro/update') }}",
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

            let id = $("#pid").val();
            apiCall("{{ url('api/v1/pro/details') }}/" + id, "Get")
                .then(function(data) {
                  //  console.log(data)
                    $("#id").val(data.data.id)
                    $("#product_name").val(data.data.product_name)
                    $("#product_unit").val(data.data.product_unit).attr('selected','selected');
                    $("#product_price").val(data.data.product_price);
                    $("#product_quantity").val(data.data.product_quantity);
                    $("#image").val(data.data.product_image);
                    html='';
                    if(data.data.product_image !=null){
                        html =`<img src="{!! asset('documents/${data.data.product_image}') !!}" width="100px" height="100px" />`
                     }
                     $("#image").append(html);
                });

            // apiCall("{{ url('api/v1/pro/consumption') }}/" + id, "Get")
            //     .then(function(data) {
            //         // console.log(data);
            //         var html ='';
            //         $.each(data.data,function(index,item){

            //                 html += `<tr>
            //                         <td>${item.raw.raw_name}</td>
            //                         <td>${item.unit}</td>
            //                         <td>${item.stock}</td>
            //                     </tr>`;
            //             });
            //             $("#attr_table").html(html);
            //     });

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
        //                // console.log(data[i].raw_id)
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
        //           <input type ="hidden" name ="raw_id[]" value ="${item.value}"/>
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
        //     $('#outputArea').html(html);
        // }


      
    </script>

@endsection
