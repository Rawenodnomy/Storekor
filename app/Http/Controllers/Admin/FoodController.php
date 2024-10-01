<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Food;


class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $products = DB::select('SELECT *, (SELECT food_categories.name FROM food_categories WHERE food_categories.id = foods.food_category_id) as category FROM products INNER JOIN foods ON foods.product_id = products.id WHERE type_id = 10');

        return view('admin.food.index', [
            'products'=>$products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $categories = DB::select('SELECT * FROM `food_categories`');
        return view('admin.food.create', [
            'categories'=>$categories
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

        date_default_timezone_set ('Asia/Yekaterinburg');
        if ($request->all()['title']==null || $request->all()['price']==null || $request->all()['text']==null || $request->all()['weight']==null || $request->all()['count']==null || empty($request->all()['img'])){
            return Redirect()->back()->withSuccess("Заполните все поля");
        }


        if ($request->all()['price']<=0){
            return Redirect()->back()->withSuccess("Некорректная цена");
        }
        if ($request->all()['weight']<=0){
            return Redirect()->back()->withSuccess("Некорректный вес");
        }
        if ($request->all()['count']<=0){
            return Redirect()->back()->withSuccess("Некорректное количество");
        }






        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);

        $photo = $permitted_chars . '_' . $_FILES['img']['name'];

        DB::insert('INSERT INTO `products` (`name`, `price`, `photo`, `description`, `type_id`, `count`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?);', [$request->all()['title'], $request->all()['price'], $photo, $request->all()['text'], 10, $request->all()['count'], date('Y-m-d')]);
        $id = DB::getPdo()->lastInsertId();
        move_uploaded_file(
            $_FILES['img']['tmp_name'],
            'products/' . $permitted_chars . '_' . $_FILES['img']['name']
        );

        DB::insert('INSERT INTO `foods` (`id`, `product_id`, `food_category_id`, `weight`) VALUES (NULL, ?, ?, ?)', [DB::getPdo()->lastInsertId(), $request->all()['category'], $request->all()['weight']]);

        

        return Redirect('/admin/foods/'.$id)->withSuccess("Товар успешно создан");
    }

    /**
     * Display the specified resource.
     */
    public function show($food)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
      
        $product = DB::select('SELECT *, (SELECT food_categories.name FROM food_categories WHERE id = (SELECT foods.food_category_id FROM foods WHERE foods.product_id = products.id)) as category, (SELECT foods.food_category_id FROM foods WHERE foods.product_id = products.id) as category_id, (SELECT foods.weight FROM foods WHERE foods.product_id = products.id) as weight FROM `products` WHERE id = ?', [$food])[0];
        
        return view('admin.food.show', [
            'product'=>$product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($food)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        // dd($food);
        $product = DB::select('SELECT *, (SELECT food_categories.name FROM food_categories WHERE id = (SELECT foods.food_category_id FROM foods WHERE foods.product_id = products.id)) as category, (SELECT foods.food_category_id FROM foods WHERE foods.product_id = products.id) as category_id, (SELECT foods.weight FROM foods WHERE foods.product_id = products.id) as weight FROM `products` WHERE id = ?', [$food])[0];
        $categories = DB::select('SELECT * FROM `food_categories`');

        return view('admin.food.edit', [
            'product'=>$product,
            'categories'=>$categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        
        if ($request->all()['title'] == null || $request->all()['price'] == null || $request->all()['weight'] == null || $request->all()['count'] == null){
            return Redirect()->back()->withSuccess("Заполните все поля");
        }


        if ($request->all()['price'] <= 0){
            return Redirect()->back()->withSuccess("Некорректная цена");
        }

        if ($request->all()['weight'] <= 0){
            return Redirect()->back()->withSuccess("Некорректный вес");
        }

        if ($request->all()['count'] < 0){
            return Redirect()->back()->withSuccess("Некорректное количество");
        }

        // dd(123);
        if ($request->all()['text']==null){
            $text=$request->all()['oldtext'];
        } else {
            $text = $request->all()['text'];
        }

        // dd($request->all(), $id);

        DB::update('UPDATE `products` SET `name` = ?, `price` = ?, `description` = ?, `count` = ? WHERE `products`.`id` = ?', [$request->all()['title'], $request->all()['price'], $text, $request->all()['count'], $id]);
        DB::update('UPDATE `foods` SET `food_category_id`=?, `weight`=? WHERE product_id = ?', [$request->all()['category'], $request->all()['weight'], $id]);


        if (!empty($request->all()['img'])){
            // dd('фото есть');
            unlink($_SERVER["DOCUMENT_ROOT"] . "/products/" . $request->all()['oldphoto']);
            
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);
    
            $photo = $permitted_chars . '_' . $_FILES['img']['name'];

            DB::update('UPDATE `products` SET `photo` = ? WHERE `products`.`id` = ?', [$photo, $id]);

            move_uploaded_file(
                $_FILES['img']['tmp_name'],
                'products/' . $permitted_chars . '_' . $_FILES['img']['name']
            );

        }

        return Redirect('/admin/foods/'.$id)->withSuccess("Товар был успешно обновлен");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // dd($id);
    }
}
