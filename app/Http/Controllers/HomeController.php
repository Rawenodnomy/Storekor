<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        // UPDATE `users` SET `contacts_id` = '9' WHERE `users`.`id` = 6;

        // $contact = DB::insert('INSERT INTO `contacts` (`id`, `city`, `address`, `FIO`, `index_mail`, `created_at`, `updated_at`) VALUES (NULL, NULL, NULL, NULL, NULL, NULL, NULL)');
        // $contact_id = DB::getPdo()->lastInsertId();


        if (auth()->user()->is_admin == 1){
        
        $users = DB::select('SELECT COUNT(*) as users FROM users');
        $types = DB::select('SELECT COUNT(*) as types FROM types');
        $products = DB::select('SELECT COUNT(*) as products FROM products');
        $groups = DB::select('SELECT COUNT(*) as `groups` FROM `groups`');
        $info = DB::select('SELECT COUNT(*) as info FROM information');
        $comments = DB::select('SELECT COUNT(*) as comments FROM comments');
        $users = $users[0];
        $types = $types[0];
        $products = $products[0];
        $groups = $groups[0];
        $info = $info[0];
        $comments = $comments[0];

        $orders = [];
        $statuses = DB::select('SELECT * FROM `statuses`');
        foreach ($statuses as $item){
            $one = DB::select('SELECT COUNT(*) as orders FROM orders WHERE status_id = ?', [$item->id]);
            $one[0]->stage = $item->stage;
            $one[0]->status_id = $item->id;
            array_push($orders, $one[0]);
        }



        return view('admin.home.index', compact('orders', 'users', 'types', 'products', 'groups', 'info', 'comments'));
        } 
        // elseif (auth()->user()->is_blocked == 1) {
        //     dd('забанен');
        // }
        else {
            $recently = session()->get('recently');

            if($recently != null){
                $recently = array_unique($recently);
                $array = [];
                foreach ($recently as $item) {
                    $prod = DB::select('SELECT * FROM products WHERE id = ?', [$item])[0];
                    array_push($array, $prod);
                }
                $recently = $array;
            }
            // dd($recently);
            session(['title'=>auth()->user()->name]);
            return view('home', compact('recently'));
        }
    }


}
