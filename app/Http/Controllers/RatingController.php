<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\Http\Resources\RatingResource;

class RatingController extends Controller
{
     public function setRating(Request $request)
    {
        $rating = Rating::create([
            'product_id'=>$request->get('product_id'),
            'comment'=>$request->get('comment'),
            'user_id'=>auth()->user()->id,
            'rating'=>$request->get('rating')
        ]);
        return $rating;
    }
    public function show(Request $request){
        $search = $request->get('id');
        $user_id = auth()->user()->id;
        return RatingResource::collection(Rating::where('user_id',$user_id)->where('product_id',$search)->get());
    }
    public function all(Request $request)
    {
        $search = $request->get('id');
        return RatingResource::collection(Rating::latest()->paginate(5)->where('product_id', $search));
    }

}
