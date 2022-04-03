<?php

namespace App\Http\Controllers\API\UserWishlist;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserWishlist\UserWishlistResource;
use App\Models\User;
use App\Models\UserWishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWishlistController extends Controller
{
    public function get(Request $request)
    {
        $request->validate([
            'limit' => ['nullable', 'integer']
        ]);
        $limit = $request->input('limit', 5);

        $user_wishlist = UserWishlist::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate($limit);
        return ResponseFormatter::success(UserWishlistResource::collection($user_wishlist)->response()->getData(true), 'success get user wishlist data');
    }

    public function show(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'product_id' => ['required', 'exists:products,id']
        ]);
        $wishlist = UserWishlist::where([['user_id', $request->user_id], ['product_id', $request->product_id]])->first();
        return ResponseFormatter::success($wishlist, 'success get wishlist data');
    }

    public function wishlist(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id']
        ]);

        $user = User::find(Auth::user()->id);
        $cek_wishlist = $user->user_wishlist()->where('product_id', $request->product_id);
        if($cek_wishlist->count() > 0) 
        {
            $user_wishlist = $cek_wishlist->first();
            $user_wishlist->delete();
            return ResponseFormatter::success(null, 'success delete user wishlist data');
        }

        $user_wishlist = $user->user_wishlist()->create([ 'product_id' => $request->product_id]);
        return ResponseFormatter::success(new UserWishlistResource($user_wishlist), 'successs create user wishlist data');
    }

    public function delete(UserWishlist $user_wishlist)
    {
        $user_wishlist->delete();
        return ResponseFormatter::success(null, 'success delete user wishlist data');
    }
}
