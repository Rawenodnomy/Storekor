<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $users = DB::select('SELECT *, (SELECT COUNT(id) FROM `orders` WHERE orders.user_id = users.id) as countOrders, users.id as user_id, users.created_at as date_reg FROM users INNER JOIN contacts ON users.contacts_id = contacts.id ORDER BY users.created_at DESC');

        return view('admin.user.index', [
            'users'=>$users
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $orders = DB::select('SELECT *, (SELECT SUM(count) FROM order_products WHERE order_products.order_id = orders.id) as count, (SELECT stage FROM statuses WHERE statuses.id = orders.status_id) as stage FROM `orders` WHERE user_id = ? ORDER BY orders.created_at DESC', [$user->id]);
        $user = DB::select('SELECT *, (SELECT COUNT(id) FROM `orders` WHERE orders.user_id = users.id) as countOrders, users.id as user_id, users.created_at as date_reg FROM users INNER JOIN contacts ON users.contacts_id = contacts.id WHERE users.id = ? ', [$user->id]);

        $user=$user[0];


        return view('admin.user.show', [
            'user'=>$user,
            'orders'=>$orders,
            
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $comments = DB::select('SELECT *, (SELECT name FROM products WHERE products.id = product_id) as product_name, (SELECT name FROM users WHERE id = user_id) as user_name FROM comments WHERE user_id = ? ORDER BY created_at DESC', [$user->id]);
        return view('admin.comment.index', [
            'comments'=>$comments
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }

        if ($user->is_blocked == 1){
            DB::update('UPDATE users SET is_blocked = ? WHERE users.id = ?', [0, $user->id]);
        } else{
            DB::update('UPDATE users SET is_blocked = ? WHERE users.id = ?', [1, $user->id]);
        }

        return redirect()->back()->withSuccess('Статус пользователя был успешно изменен');

    }
}
