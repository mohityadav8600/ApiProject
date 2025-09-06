<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;
class FoodController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
       public function index()
    {
        // fetch all food items
        $food = Food_details::all();

        // return directly (flat array, not wrapped in ["food" => ...])
        return $this->sendResponse($food, 'Food details retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'rate'        => 'required|numeric',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
          

            return $this->sendError('Validation Error.', $validator->errors());
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/food_images'), $filename);
            $imagePath = 'upload/food_images/' . $filename;
        }

        $food = Food_details::create([
            'name'        => $request->name,
            'description' => $request->description,
            'rate'        => $request->rate,
            'image'       => $imagePath,
        ]);

        // return with full URL for image
        $food->image = $food->image ? asset($food->image) : null;

        
        
        return $this->sendResponse($food, 'Food item created successfully');
    }

    /**
     * Display the specified resource.
     */

public function show(string $id)

{
    $food = Food_details::select('id', 'name', 'description', 'rate', 'image')
        ->where('id', $id)
        ->first();

    if (!$food) {
        return $this->sendError('Food item not found.', [], 404);
    }

    return $this->sendResponse($food, 'Selected food details retrieved successfully.');
}

    

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, string $id)
{
    $validator = Validator::make($request->all(), [
        'name'        => 'required|string|max:255',
        'description' => 'required|string',
        'rate'        => 'required|numeric',
        'image'       => 'nullable|image'
    ]);

    if ($validator->fails()) {
      
      return $this->sendError('Validation Error.', $validator->errors());

    }

    $food = Food_details::find($id);
    if (!$food) {
        return response()->json([
            'success' => false,
            'message' => 'Food item not found.'
        ], 404);
    }

    $imagePath = $food->image;

    if ($request->hasFile('image')) {
        // delete old
        if (!empty($food->image) && file_exists(public_path($food->image))) {
            @unlink(public_path($food->image));
        }

        $file     = $request->file('image');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move(public_path('upload/food_images'), $filename);

        $imagePath = 'upload/food_images/' . $filename;
    }

    $food->update([
        'name'        => $request->name,
        'description' => $request->description,
        'rate'        => $request->rate,
        'image'       => $imagePath,
    ]);


   return $this->sendResponse($food, 'Food item updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $food = Food_details::find($id);

    if (!$food) {
      
        return $this->sendError('Food item not found.', [], 404);
    }

    // ✅ Delete old image if exists
    if ($food->image && file_exists(public_path($food->image))) {
        unlink(public_path($food->image));
    }

    // ✅ Delete record
    $food->delete();

  

    return $this->sendResponse([], 'Food details deleted successfully.');
}
}
