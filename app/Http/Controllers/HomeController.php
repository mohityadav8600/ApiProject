<?php

namespace App\Http\Controllers;

use App\Models\Food_details;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class HomeController extends Controller
{
    public function register(){
        return view("register");
    }

    public function index(){
    
        return view('layouts.main');
    }

       public function ShowFood() {
        $food=Food_details::all();
             
       return view('layouts.ShowFood', compact('food') );
   }

    public function addFood(){
    
        return view('layouts.addFood');
    }


   
public function EditFood($id)
{
    $food = Food_details::find($id);
    return view('layouts.editfood', compact('food')); // Correct: no quotes around $food
}

}
