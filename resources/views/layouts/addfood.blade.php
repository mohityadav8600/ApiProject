@extends('layouts.master')

@section('title', 'Add Food')

@section('content')
<head> 
 <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="container mt-4">
    <h2>Add Food</h2>

    <form id="addFoodForm" enctype="multipart/form-data">
        <!-- Food Name -->
        <div class="mb-3">
            <label>Food Name</label>
            <input type="text" id="foodName" name="name" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label>Description</label>
            <textarea id="foodDescription" name="description" class="form-control" required></textarea>
        </div>

        <!-- Rate -->
        <div class="mb-3">
            <label>Rate</label>
            <input type="number" id="foodRate" name="rate" class="form-control" required>
        </div>

        <!-- Food Image -->
        <div class="mb-3">
            <label>Food Image</label><br>
            <img id="previewImage" 
                 src="/assets/img/no-image.png" 
                 width="120" class="mb-2"><br>
            <input type="file" id="foodImage" name="image" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Add Food</button>
        <a href="/showfood" class="btn btn-secondary">Cancel</a>
    </form>

    <!-- Toast -->
    <div id="toast" style="position: fixed; top: 20px; right: 20px; display:none; background: #333; color: #fff; padding: 10px 20px; border-radius: 5px;"></div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    // ✅ CSRF token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ✅ Image preview when file selected
    $("#foodImage").on("change", function() {
        let reader = new FileReader();
        reader.onload = (e) => {
            $("#previewImage").attr("src", e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
    });

    // ✅ Form submit (Add Food)
   $('#addFoodForm').submit(function (event) {
    event.preventDefault();

    var form = $('#addFoodForm')[0];
    var data = new FormData(form);

    $.ajax({
        type: 'POST',
        url: 'api/food',   // ✅ web.php route (NOT api.php)
        data: data,
        processData: false, 
        contentType: false,
        success: function(response) {
            // ✅ Show toast
            $('#toast').text(response.message).fadeIn().delay(1200).fadeOut();

            // ✅ Redirect to showfood after short delay
            setTimeout(function () {
                window.location.href = "/showfood";
            }, 1500);
        },
        error: function(err) {
            let errMsg = err.responseJSON?.message || "Add food failed";
            $('#toast').text("Error: " + errMsg).fadeIn().delay(3000).fadeOut();
        }
    });
});
});
</script>