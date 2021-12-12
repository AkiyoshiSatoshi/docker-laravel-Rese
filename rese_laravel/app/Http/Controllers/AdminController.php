<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Like;
use App\Models\Shop;
use App\Models\Test;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function adminindex()
    {
        return view('admin.index');
    }

    public function createShop(Request $request)
    {
        $url = $request->file('img_url')->getClientOriginalName();
        Storage::disk('public')->putFileAs('img/shop',$request->img_url, $url);
        $item = Auth::id();
        $shop = new Shop;
        $param = [
            "name" => $request->shop_name,
            "img_url" => $url,
            "description" => $request->description,
            "area_id" => $request->area,
            "genre_id" => $request->genre,
            "owner_id" => Auth::id()
        ];
        $shop->fill($param)->save();
        $adminRepre = Auth::user()->access_auth;
        if ( $adminRepre == 1 ) {
            echo "authentication success";
            return view('admin.repre');
        } else {
            return "Not access";
        }
    }

    public function updateshop(Request $request)
    {
        $shop = Shop::where('owner_id', $request->owner_id)->first();
        $form = $request->all();
        unset($form['_token']);
        $shop->fill($form)->save();
        dd($shop);
    }
}
