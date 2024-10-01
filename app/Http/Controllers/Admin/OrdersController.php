<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Status;
use App\Models\Order;


class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $id)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }

        $orders = DB::select('SELECT *, (SELECT SUM(count) FROM order_products WHERE order_products.order_id = orders.id) as count, (SELECT stage FROM statuses WHERE id = status_id) as stage FROM orders WHERE status_id = ? ORDER BY created_at DESC', [$id->id]);

        $stage = DB::select('SELECT stage FROM statuses WHERE id = ?', [$id->id]);
        $stage = $stage[0];

        return view('admin.order.index', [
            'orders'=>$orders,
            'stage'=>$stage
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // $ids = '';
        // $products_id = DB::select('SELECT product_id FROM order_products WHERE order_id = ?', [$order->id]);
        // // dd($products_id);

        // foreach ($products_id as $id){
        //     $ids = $ids . $id->product_id . ', ';
        //     // var_dump($id->product_id);
        // }

        // $ids = substr($ids, 0, -2); 
        // $query = "SELECT *, (SELECT group_name FROM `groups` WHERE groups.id = products.group_id) as group_name, (SELECT type_products FROM types WHERE types.id = products.type_id) as type FROM products WHERE id IN (" . $ids . ")";
        // $products = DB::select($query);
        // dd($products);
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }


        $order_products = DB::select('SELECT *, order_products.count as count_order, (SELECT versions.version_name FROM versions WHERE versions.id = order_products.version_id) as version_name, (SELECT types.type_products FROM types WHERE types.id = products.type_id) as type_name, (SELECT versions.count FROM versions WHERE versions.id = order_products.version_id) as version_count,(SELECT groups.group_name FROM `groups` WHERE groups.id = products.group_id) as group_name FROM order_products INNER JOIN products ON order_products.product_id = products.id WHERE order_id = ?', [$order->id]);
        // $order_id = $order->id; 

        $order = DB::select('SELECT *, (SELECT SUM(count) FROM order_products WHERE order_products.order_id = orders.id) as total_count FROM orders WHERE id = ?', [$order->id]);

        $order = $order[0];
        // dd($order);
        $user = DB::select('SELECT *, (SELECT COUNT(id) FROM `orders` WHERE orders.user_id = users.id) as countOrders, users.id as user_id, users.created_at as date_reg FROM users INNER JOIN contacts ON users.contacts_id = contacts.id WHERE users.id = ? ', [$order->user_id])[0];

        return view('admin.order.show', [
            'order_products'=>$order_products,
            'order'=>$order,
            'user'=>$user
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }

        if($request->all()['status']==1){
            DB::update('UPDATE `orders` SET `status_id` = 2, `track_code` = ? WHERE orders.id = ?', [$request->all()['track'], $request->all()['order_id']]);
            $products = DB::select('SELECT * FROM `order_products` WHERE order_id = ?', [$request->all()['order_id']]);

        } elseif ($request->all()['status'] == 2) {
            DB::update('UPDATE `orders` SET `status_id` = 3 WHERE orders.id = ?', [$request->all()['order_id']]);
        } elseif ($request->all()['status'] == 3) {
            DB::update('UPDATE `orders` SET `status_id` = 4 WHERE orders.id = ?', [$request->all()['order_id']]);
        }

       



        return Redirect()->back()->withSuccess("Заказ был успешно обновлен");
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }

        $products = DB::select('SELECT * FROM `order_products` WHERE order_id = ?', [$order->id]);

        foreach ($products as $item){

            if ($item->version_id==null){
                $count = DB::select('SELECT count FROM `products` WHERE id = ?', [$item->product_id])[0]->count;
                $newCount = $count+$item->count;
                DB::update('UPDATE `products` SET `count` = ? WHERE `products`.`id` = ?', [$newCount, $item->product_id]);
            } else {
                // dd($item);
                $count = DB::select('SELECT *, (SELECT albums.product_id FROM albums WHERE albums.id = versions.album_id) as prodid FROM `versions` HAVING prodid = ? AND id = ?', [$item->product_id, $item->version_id])[0]->count;
                $newCount=$count+$item->count;
                DB::update('UPDATE `versions` SET `count` = ? WHERE `versions`.`id` = ?', [$newCount, $item->version_id]);
            }   

        }



        DB::update('UPDATE `orders` SET status_id = 6 WHERE orders.id = ?', [$order->id]);

        $orders = DB::select('SELECT *, (SELECT SUM(count) FROM order_products WHERE order_products.order_id = orders.id) as count, (SELECT stage FROM statuses WHERE id = status_id) as stage FROM orders WHERE status_id = ? ORDER BY created_at DESC', [6]);

        // dd($orders);

        return Redirect()->back()->withSuccess("Статус заказа был успешно обновлен");
        

    }
}
