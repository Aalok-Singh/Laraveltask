@extends('layouts.master')

@section('content')
<div class="container">
    <div id="result"></div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Product</div>
                <div class="card-body">
                    <form id="uploadProduct" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="product_id" value="{{$products->id}}">
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" value="{{$products->product_name}}">
                        </div>
                        <div class="mb-3">
                            <label for="product_price" class="form-label">Product Price</label>
                            <input type="number" class="form-control" id="product_price" name="product_price" value="{{$products->product_price}}">
                        </div>
                        <div class="mb-3">
                            <label for="product_descrition" class="form-label">Product Description</label>
                            <textarea class="form-control" id="product_descrition" name="product_descrition" rows="6">{{$products->product_desccription}}</textarea>
                        </div>
                        <div style="overflow-y: scroll; height:200px;" id="here">
                            <div class="card-group">
                                @foreach($products['productimages'] as $productimages)
                                <div class="card" id="porductimage{{$productimages->id}}" style="margin-left:10px">
                                    <img class="card-img-top" src="{{ url('my_files/' . $productimages->product_image) }}" alt="Product Images" height="100px" width="auto">
                                    <div class="card-body">
                                    <a class="btn btn-danger btn-sm" title="Delete Product" onclick="deleteImage({{$productimages->id}},{{$productimages->product_id}})" >Delete</a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="product_images" class="form-label">Product Images(Multiple)</label>
                            <input class="form-control" type="file" id="product_images" name="product_images[]" multiple>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $("#uploadProduct").validate({
            rules: {
                'product_name': {
                    required: true,
                },
                'product_price': {
                    required: true,
                },
            },
            messages: {
                'product_name': {
                    required: "Please Enter Product Name",
                },
                'product_price': {
                    required: "Please Enter Product Price",
                },
                
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                let formData = new FormData(form);

                const totalImages = $("#product_images")[0].files.length;
                let images = $("#product_images")[0];

                for (let i = 0; i < totalImages; i++) {
                    formData.append('images' + i, images.files[i]);
                }
                formData.append('totalImages', totalImages);

                console.log(formData);

                $.ajax({
                    url: "{{ route('editproduct') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    cache: false,
                    contentType: false,
                    success: function(response) {
                        form.reset();
                        if (response && response.status === 'success') {
                            $("#result").append(`<div class='alert alert-success alert-dismissible fade show' role='alert'>${response.message}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>`);

                            setTimeout(() => {
                                $(".alert").remove();
                            }, 5000);
                            window.location = "/";
                        } else if (response.status === 'failed') {
                            $("#result").append(`<div class='alert alert-success alert-dismisisble fade show' role='alert'>${response.message}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>`);

                            setTimeout(() => {
                                $(".alert").remove();
                            }, 5000);
                        }
                    }
                });
            }
        });
    });
    function deleteImage(imageid,productid) {
        var deleteConfirm = confirm("Are you sure?");
        if (deleteConfirm == true) {
            // AJAX request
            $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
            $.ajax({
                url: "{{ route('deleteimage') }}",
                type: 'post',
                data: {
                    id: imageid,
                    productid:productid
                },
                success: function(response) {
                    if (response && response.status === 'success') {
                        $("#porductimage"+imageid).hide();
                        $("#result").append(`<div class='alert alert-success alert-dismissible fade show' role='alert'>${response.message}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>`);
                        setTimeout(() => {
                            $(".alert").remove();
                        }, 5000);
                    }
                }
            });
        }

    }
</script>
@endsection