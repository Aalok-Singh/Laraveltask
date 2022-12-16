@extends('layouts.master')
@section('content')
<div class="container">
    <div id="result"></div>
    <div >
        <a href="/addproduct" class="btn btn-primary" style="position: absolute;
right:    149px;
">Add Product</a>
        </div>
    <div class="row justify-content-center">
        <h2>Product Data</h2>
        
        <table class="table table-bordered" id="product_data">
            <thead>
                <tr>
                    <th>#Id</th>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Product Description</th>
                    <th>Action</th>
                </tr>
            </thead>
    </div>
</div>

@endsection
@section('scripts')
<script>
    var otable = '';
    $(document).ready(function() {
        var otable = $('#product_data').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('products-list') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'product_name',
                    name: 'product_name'
                },
                {
                    data: 'product_price',
                    name: 'product_price'
                },
                {
                    data: 'product_desccription',
                    name: 'product_desccription'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
    function deleteproduct(id) {
        var deleteConfirm = confirm("Are you sure?");
        if (deleteConfirm == true) {
            // AJAX request
            $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
            $.ajax({
                url: "{{ route('deleteproduct') }}",
                type: 'post',
                data: {
                    id: id
                },
                success: function(response) {
                    if (response && response.status === 'success') {
                        $('#product_data').DataTable().ajax.reload();
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