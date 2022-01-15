@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">
            <input type="hidden" id="pid" value="{{ $id }}" />

            <div class="pd-20 card-box mb-30 row">
                 <div class="col-md-2">
                     <div class="form-group row">
                        <div id="image"></div>
                    </div>
                 </div>
                <div class="col-md-10">
                <form id="myform" method="POST" enctype="multipart/form-data">
                    <div class="form-group row">
                        <input class="form-control" type="hidden" name="id" id="product_id"
                                required />
                        <label class="col-sm-12 col-md-2 col-form-label">Product Name<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" name="product_name" id="product_name"
                                placeholder="Name" required readonly/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Product Unit<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10" style="width:100%;">

                            <select
                                name="product_unit" id="product_unit" class="form-control"  required="required" readonly disabled>
                                <option value=""  selected>Select Unit</option>
                                <option value="kg">KG</option>
                                <option value="mg">Mg</option>
                                <option value="li">Litre</option>
                                <option value="ml">Mili Liter</option>
                                <option value="pcs">Pcs</option>
                            </select>
                        </div>
                    </div>
                    <div id="outputArea"></div>
                     <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Product Price<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="number" name="product_price" id="product_price" placeholder="Price" required readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-6 col-md-2 col-form-label">Current Quantity</label>
                        <div class="col-sm-12 col-md-2">
                            <input class="form-control" type="number" placeholder="Quantity" name="product_quantity"
                                id="product_quantity" disabled>
                        </div>
                        +
                         <label class="col-sm-6 col-md-2 col-form-label">Update Quantity<small style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-2">
                            <input class="form-control" type="number" placeholder="Quantity" name="update_quantity"
                                id="update_quantity" style="border: 3px solid #555;" >
                        </div>
                        =
                         <label class="col-sm-6 col-md-2 col-form-label"> <span id="ttlQty" class="badge" style="background-color: grey;border: 3px solid #555;"></span></label>

                    </div>
                   
                   

                    <div class="form-group row">
                        <div class="col-sm-12 col-md-10">
                            <input class="btn btn-primary" type="submit" value="Update">
                        </div>
                    </div>


                </form>
                </div>

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
               
            }
        });
    </script>
    <script type="text/javascript">
     var arr=[];

        $(document).ready(function() {

          
           


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
                    let qty = $("#update_quantity").val();
                    let productId = $("#product_id").val();

                    apiCall("{{ url('api/v1/product/update') }}", form.method,{
                        "_token": '{{ csrf_token() }}',
                        product_id: productId,
                        product_quantity :Math.abs(qty)
                    })
                .then(function(data) {
                    $(form)[0].reset()
                     swal({
                    position: 'top-end',
                    type: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 3000
                });
                    
                   window.location = "{{ route('warehouse.product.list') }}"
                });
                }
            })

            let id = $("#pid").val();
            apiCall("{{ url('api/v1/pro/details') }}/" + id, "Get")
                .then(function(data) {

                    $("#product_id").val(data.data.id)
                    $("#product_name").val(data.data.product_name)
                    $("#product_unit").val(data.data.product_unit).attr('selected','selected');
                    $("#product_price").val(data.data.product_price);
                    $("#product_quantity").val(data.data.product_quantity);
                    $("#image").val(data.data.product_image);
                    html='';
                    if(data.data.product_image !=null){
                        html =`<img src="{!! asset('documents/${data.data.product_image}') !!}" width="200px" height="200px" />`
                     }
                     $("#image").append(html);
                });
        });


         $(document).ready(function(){
          
  $("#update_quantity").keyup(function(){
     var qty = $("#product_quantity").val();
    var newQty = $('#update_quantity').val();
   
   var totQty= parseFloat(qty)+parseFloat(Math.abs(newQty));
   setTimeout(function() {
        $("#ttlQty").html(`Total Quantity:${totQty}`);
                
            }, 1000);

  });
});
    </script>

@endsection
