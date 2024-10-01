<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index() {

        $orders = DB::select('SELECT COUNT(*) as orders FROM orders WHERE status_id = 1');

        $users = DB::select('SELECT COUNT(*) as users FROM users');
        $types = DB::select('SELECT COUNT(*) as types FROM types');
        $products = DB::select('SELECT COUNT(*) as products FROM products');
        $groups = DB::select('SELECT COUNT(*) as `groups` FROM `groups`');
        $info = DB::select('SELECT COUNT(*) as info FROM information');
        $comments = DB::select('SELECT COUNT(*) as comments FROM comments');
        $orders = $orders[0];
        $users = $users[0];
        $types = $types[0];
        $products = $products[0];
        $groups = $groups[0];
        $info = $info[0];
        $comments = $comments[0];

        dd($orders);

        return view('admin.home.index', compact('orders', 'users', 'types', 'products', 'groups', 'info', 'comments'));
    }



    // public function index(){
    //     $posts_count = Post::all()->count();
    //     $categories_count = Categorie::all()->count();
    //     return view('admin.home.index', [
    //         'posts_count'=>$posts_count,
    //         'categories_count'=>$categories_count
    //     ]);
    // }
}
