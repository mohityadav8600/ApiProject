@extends('layouts.master')

@section('title', 'Edit Food')

@section('content')
<head>  
 <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="container mt-4">
    <h2>Edit Food</h2>

    <form id="editFoodForm" enctype="multipart/form-data">
       
        <!-- Hidden ID from route -->
        <input type="hidden" id="foodId" value="{{ $food->id }}">

        <!-- Food Name -->
        <div class="mb-3">
            <label>Food Name</label>
            <input type="text" id="foodName" name="name" class="form-control" value="{{ $food->name }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label>Description</label>
            <textarea id="foodDescription" name="description" class="form-control" required>{{ $food->description }}</textarea>
        </div>

        <!-- Rate -->
        <div class="mb-3">
            <label>Rate</label>
            <input type="number" id="foodRate" name="rate" class="form-control" value="{{ $food->rate }}" required>
        </div>

        <!-- Food Image -->
        <div class="mb-3">
            <label>Food Image</label><br>
            <img id="previewImage" 
                 src="{{ $food->image ? '/' . $food->image : '/assets/img/no-image.png' }}" 
                 width="120" class="mb-2"><br>
            <input type="file" id="foodImage" name="image" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary" id="submitBtn">Update</button>
        <a href="/food" class="btn btn-secondary">Cancel</a>
    </form>

    <!-- Toast / Alert -->
    <div id="toast" style="position: fixed; top: 20px; right: 20px; display:none; background: #333; color: #fff; padding: 10px 20px; border-radius: 5px;"></div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#editFoodForm').submit(function (event) {
        event.preventDefault();

        var foodId = $('#foodId').val();
        var form = $('#editFoodForm')[0];
        var data = new FormData(form);
        data.append('_method', 'PUT'); // simulate PUT

        $.ajax({
            type: 'POST', // because of _method
            url: `/api/food/${foodId}`,
            data: data,
            processData: false,
            contentType: false,
            success: function(response) {
                // ✅ Update preview image immediately if new one was uploaded
                if (response.data && response.data.image) {
                    $('#previewImage').attr('src', '/' + response.data.image);
                }

                // ✅ Show toast
                $('#toast').text(response.message).fadeIn().delay(2000).fadeOut();

                // ✅ Redirect to food list page after short delay
                setTimeout(function () {
                    window.location.href = "/showfood";
                    // If you want single food page:
                    // window.location.href = "/food/" + foodId;
                }, 2200);
            },
            error: function(err) {
                let errMsg = err.responseJSON?.message || "Update failed";
                $('#toast').text("Error: " + errMsg).fadeIn().delay(4000).fadeOut();
            }
        });
    });
});
</script>
