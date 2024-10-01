<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $products = DB::select('SELECT *, (SELECT group_name FROM `groups` WHERE groups.id = products.group_id) as group_name, (SELECT type_products FROM types WHERE types.id = products.type_id) as type, (SELECT direction FROM types WHERE types.id = products.type_id) as direction FROM products having direction = "kpop" ORDER BY created_at DESC');
        
        foreach ($products as $product){
            if($product->type_id==3){
                $id_alb = DB::select('SELECT id FROM albums WHERE product_id = ?', [$product->id])[0]->id;
    
                $count = DB::select('SELECT SUM(count) as count FROM `versions` WHERE album_id = ?', [$id_alb])[0]->count;
                $product->count=$count;
            }
        }


        return view('admin.product.index', [
            'products'=>$products
        ]);
        // dd($products);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $types = DB::select('SELECT * FROM types WHERE direction = "kpop"');

        $groups = DB::select('SELECT id, group_name FROM `groups`');


        return view('admin.product.create', [
            'types'=>$types,
            'groups'=>$groups
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        
        if ($request->all()['title']==null || $request->all()['price']==null || $request->all()['text']==null || empty($request->all()['img'])){
            return Redirect()->back()->withSuccess("Заполните все поля");
        } 

        if ($request->all()['price']<=1){
            return Redirect()->back()->withSuccess("Цена должна быть больше нуля");
        }

        if (empty($request->all()['img'])){
            return Redirect()->back()->withSuccess("Выберите фото");
        }

        if ($request->all()['group_id'] == 'null'){
            $group = null;
        } else {
            $group = $request->all()['group_id'];
        }

        if ($request->all()['type_id']==4 && $request->all()['count']==null){
            return Redirect()->back()->withSuccess("Укажите количество карточек");
        } 

        if ($request->all()['type_id']==4 && $request->all()['count']<=0){
            return Redirect()->back()->withSuccess("Некорректное количество");
        }

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);

        $photo = $permitted_chars . '_' . $_FILES['img']['name'];
        DB::insert('INSERT INTO `products` (`id`, `name`, `price`, `photo`, `description`, `type_id`, `count`, `group_id`, `created_at`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)', [$request->all()['title'], $request->all()['price'], $photo, $request->all()['text'], $request->all()['type_id'], $request->all()['count'], $group, date('Y-m-d')]);

        $id = DB::getPdo()->lastInsertId();
        
        if ($request->all()['type_id']==3){
            $prodid = DB::getPdo()->lastInsertId();

            DB::insert('INSERT INTO albums (id, product_id) VALUES (NULL, ?)', [$prodid]);
        }


        if (!empty($_FILES)){
            move_uploaded_file(
                $_FILES['img']['tmp_name'],
                'products/' . $permitted_chars . '_' . $_FILES['img']['name']
            );
        }
        // dd($request->all()['title'], $request->all()['price'], $photo, $request->all()['text'], $request->all()['type_id'], $request->all()['count'], $request->all()['group_id'], date('Y-m-d'));

        
        return Redirect('/admin/products/'.$id)->withSuccess("Товар был успешно добавлен");
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $product = DB::select('SELECT *, (SELECT group_name FROM `groups` WHERE id = group_id) as group_name, (SELECT type_products FROM types WHERE id = type_id) as type_products FROM products WHERE id = ?', [$product->id]);
        $product=$product[0];

        if($product->type_id==3){
            $id_alb = DB::select('SELECT id FROM albums WHERE product_id = ?', [$product->id])[0]->id;

            $count = DB::select('SELECT COUNT(*) as count FROM `versions` WHERE album_id = ?', [$id_alb])[0]->count;
            $product->count=$count;
            $allCount = DB::select('SELECT SUM(count) as count FROM `versions` WHERE album_id = ?', [$id_alb])[0]->count;
        } else {
            $allCount = false;
        }
       
        return view('admin.product.show', [
            'product'=>$product,
            'allCount'=>$allCount,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }


        $product = DB::select('SELECT *, (SELECT group_name FROM `groups` WHERE groups.id = products.group_id) as group_name, (SELECT type_products FROM types WHERE types.id = products.type_id) as type FROM products WHERE id = ?', [$id]);
        $product = $product[0];

        $types = DB::select('SELECT * FROM types WHERE id != ? AND direction =?', [$product->type_id, 'kpop']);
        $groups = DB::select('SELECT id, group_name FROM `groups` WHERE id !=?', [$product->group_id]);

        return view('admin.product.edit', [
            'product'=>$product,
            'types'=>$types,
            'groups'=>$groups,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        // dd($request->all());
        if ($request->all()['title']==null || $request->all()['price']==null){
            return Redirect()->back()->withSuccess("Заполните все поля");
        }

        if ($request->all()['type_id']==4 && $request->all()['count']==null){
            return Redirect()->back()->withSuccess("Укажите количество карточек");
        } 

        if ($request->all()['type_id']==4 && $request->all()['count']<0){
            return Redirect()->back()->withSuccess("Некорректное количество");
        }
        if ($request->all()['price']<=1){
            return Redirect()->back()->withSuccess("Цена должна быть больше нуля");
        }

        if ($request->all()['text'] == null){
            $text = $request->all()['oldtext'];
        } else {
            $text = $request->all()['text'];
        }
        
        if ($request->all()['type_id'] == 3){
            $count = null;
        } else {
            $count = $request->all()['count'];
        }

        if ($request->all()['group_id'] == 'null'){
            $group = null;
        } else {
            $group = $request->all()['group_id'];
        }

        $back = DB::select('SELECT * FROM `products` WHERE id = ?', [$product->id])[0]->type_id;

        // dd($request->all(), $back);

        if ($back==3 && $request->all()['type_id']==4){
            DB::delete('DELETE FROM `albums` WHERE `product_id` = ?', [$product->id]);
        } elseif ($back==4 && $request->all()['type_id']==3) {
            DB::insert('INSERT INTO `albums` (`product_id`) VALUES (?)', [$product->id]);
        }


        if (empty($request->all()['img'])){
            DB::update('UPDATE `products` SET `name` = ?, `price` = ?, `description` = ?, `type_id` = ?, `count` = ?, `group_id` = ? WHERE `products`.`id` = ?', [$request->all()['title'], $request->all()['price'], $text, $request->all()['type_id'], $count, $group,  $product->id]);
        }  else {
            $oldphotoname = DB::select('SELECT photo FROM products WHERE id = ?', [$product->id])[0]->photo;
            unlink("products/".$oldphotoname);


            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);
    
            $photo = $permitted_chars . '_' . $_FILES['img']['name'];

            DB::update('UPDATE `products` SET `name` = ?, `price` = ?, `description` = ?, `type_id` = ?, `count` = ?, `group_id` = ?, photo = ? WHERE `products`.`id` = ?', [$request->all()['title'], $request->all()['price'], $text, $request->all()['type_id'], $count, $group, $photo, $product->id]);
        
        
            if (!empty($_FILES)){
                move_uploaded_file(
                    $_FILES['img']['tmp_name'],
                    'products/' . $permitted_chars . '_' . $_FILES['img']['name']
                );
            }

        }

        return Redirect('/admin/products/'. $product->id)->withSuccess(" Товар был успешно обновлен");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        if ($product->type_id==3){
            DB::delete('DELETE FROM `albums` WHERE `product_id` = ?', [$product->id]);
        }
        unlink("products/".$product->photo);
        $product->delete();
        return redirect()->back()->withSuccess(' Товар был успешно удален');
    }


}
