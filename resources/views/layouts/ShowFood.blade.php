@extends('layouts.master')

@section('title', 'Show Food')

@section('content')


<meta name="csrf-token" content="{{ csrf_token() }}">



<style>
.food-img {
    width: 100%;              /* always stretch to container */
    max-height: 200px;        /* limit height but don't force */
    object-fit: cover;        /* crop large images nicely */
    background-color: #f8f9fa;
    display: block;
}

</style>
    <!-- Food Section -->
    <section class="ex-collection section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header-left mb-4">
                        <h3 class="text-light-black header-title title">Explore Our Food Items</h3>
                    </div>
                </div>
            </div>

            <div class="row" id="foodContainer">
                <!-- Food items will be inserted here via JS -->
            </div>
        </div>
    </section>
@endsection

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {

    // 🔑 Replace with your actual stored token
    let token = localStorage.getItem("auth_token"); 

    if (!token) {
        console.error("No Sanctum token found. Please login first.");
        $('#foodContainer').html("<p class='text-center text-danger'>You must login to see food items.</p>");
        return;
    }

    $.ajax({
        type: "GET",
        url: "http://127.0.0.1:8000/api/food",
        headers: {
            "Accept": "application/json",
            "Authorization": "Bearer " + token   // ✅ Attach Sanctum token
        },
    
        
        success: function (res) {
            
            console.log("API Response:", res);

            let foodContainer = $('#foodContainer');
            foodContainer.html(""); // clear old

            if (res.success && res.data.length > 0) {
                let html = "";
                res.data.forEach(food => {
                    html += `
                         
                        <div class="col-md-4 mb-4" id="food-${food.id}">
                            <div class="ex-collection-box mb-xl-20 shadow rounded position-relative overflow-hidden">

                                <!-- Food Image -->
                                <img src="${food.image ? '/' + food.image : '/assets/img/no-image.png'}" 
                                    class="food-img rounded-top" alt="${food.name}">

                                <!-- Food Info -->
                                <div class="p-3 bg-white rounded-bottom">
                                    <div class="mb-2">
                                        <a href="/food/${food.id}" class="category-btn h5 text-dark m-0">${food.name}</a>
                                    </div>

                                    <p class="fw-bold mb-2">Rate: ₹${food.rate}</p>

                                    <div class="d-flex justify-content-between">
                                        <a href="/editfood/${food.id}" class="btn btn-sm btn-warning">Edit</a>
                                        <button onclick="deleteFood(${food.id})" class="btn btn-sm btn-danger">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                                        `;
                });
                foodContainer.html(html); // inject once
            } else {
                foodContainer.html("<p class='text-center text-muted'>No food items available.</p>");
            }
        },
        error: function (e) {
            console.error("Load Error:", e.responseText);
            $('#foodContainer').html("<p class='text-center text-danger'>Failed to load food items.</p>");
        }
    });
});

//Delete Food


function deleteFood(foodId) {
    if (!confirm("Are you sure you want to delete this food item?")) return;

    let token = localStorage.getItem("auth_token");
    let csrf = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: "http://127.0.0.1:8000/api/food/" + foodId,
        type: "DELETE",
        headers: {
            "Accept": "application/json",
            "Authorization": "Bearer " + token,
            "X-CSRF-TOKEN": csrf   // ✅ add csrf token
        },
        success: function (res) {
            alert(res.message || "Food deleted successfully");
            $("#food-" + foodId).remove();
        },
        error: function (xhr) {
            console.error("Delete Error:", xhr.responseText);
            let errMsg = xhr.responseJSON?.message || "Unable to delete food";
            alert("Error: " + errMsg);
        }
    });

}

</script>