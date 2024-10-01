<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cosmetic;

class CosmeticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $products = DB::select('SELECT *, 
        (SELECT areas.zone FROM areas WHERE areas.id = cosmetics.area_id) as area,
        (SELECT spfs.spf FROM spfs WHERE spfs.id = cosmetics.spf_id) as spf,
        (SELECT textures.texture FROM textures WHERE textures.id = cosmetics.texture_id) as texture,
        (SELECT brands.name FROM brands WHERE brands.id = cosmetics.brand_id) as brand,
        (SELECT formats.format FROM formats WHERE formats.id = cosmetics.format_id) as format
        FROM products INNER JOIN cosmetics ON cosmetics.product_id = products.id WHERE type_id = 11');
        
        return view('admin.cosmetic.index', [
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
        $areas = DB::select('SELECT * FROM areas');
        $brands = DB::select('SELECT * FROM brands');
        $formats = DB::select('SELECT * FROM formats');
        $spfs = DB::select('SELECT * FROM spfs');
        $textures = DB::select('SELECT * FROM textures');

        return view('admin.cosmetic.create', [
            'areas'=>$areas,
            'brands'=>$brands,            
            'formats'=>$formats,
            'spfs'=>$spfs,
            'textures'=>$textures
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


        if ($request->all()['title'] == null || $request->all()['price'] == null || $request->all()['text'] == null || $request->all()['volume'] == null || $request->all()['count'] == null || empty($request->all()['img'])){
            return Redirect()->back()->withSuccess("Заполните все поля");
        }


        if ($request->all()['price'] <= 0){
            return Redirect()->back()->withSuccess("Некорректная цена");
        }

        if ($request->all()['volume'] <= 0){
            return Redirect()->back()->withSuccess("Некорректный объем");
        }

        if ($request->all()['count'] <= 0){
            return Redirect()->back()->withSuccess("Некорректное количество");
        }
















        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);

        $photo = $permitted_chars . '_' . $_FILES['img']['name'];

        DB::insert('INSERT INTO `products` (`id`, `name`, `price`, `photo`, `description`, `type_id`, `count`, `created_at`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)', [$request->all()['title'], $request->all()['price'], $photo, $request->all()['text'], 11, $request->all()['count'], date('Y-m-d')]);

        $id = DB::getPdo()->lastInsertId();
        $prod = DB::getPdo()->lastInsertId();

        DB::insert('INSERT INTO `cosmetics` (`id`, `product_id`, `area_id`, `spf_id`, `texture_id`, `brand_id`, `format_id`, `volume`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)', [$prod, $request->all()['area'], $request->all()['spf'], $request->all()['texture'], $request->all()['brand'], $request->all()['format'], $request->all()['volume']]);

        move_uploaded_file(
            $_FILES['img']['tmp_name'],
            'products/' . $permitted_chars . '_' . $_FILES['img']['name']
        );

        return Redirect('/admin/cosmetics/'.$id)->withSuccess("Товар был успешно добавлен");
    }

    /**
     * Display the specified resource.
     */
    public function show($cosmetic)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $product = DB::select('SELECT *, 
        (SELECT areas.zone FROM areas WHERE areas.id = cosmetics.area_id) as area,
        (SELECT spfs.spf FROM spfs WHERE spfs.id = cosmetics.spf_id) as spf,
        (SELECT textures.texture FROM textures WHERE textures.id = cosmetics.texture_id) as texture,
        (SELECT brands.name FROM brands WHERE brands.id = cosmetics.brand_id) as brand,
        (SELECT formats.format FROM formats WHERE formats.id = cosmetics.format_id) as format
        FROM products INNER JOIN cosmetics ON cosmetics.product_id = products.id WHERE product_id = ?', [$cosmetic])[0];

        return view('admin.cosmetic.show', [
            'product'=>$product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cosmetic)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $product = DB::select('SELECT *, 
        (SELECT areas.zone FROM areas WHERE areas.id = cosmetics.area_id) as area,
        (SELECT spfs.spf FROM spfs WHERE spfs.id = cosmetics.spf_id) as spf,
        (SELECT textures.texture FROM textures WHERE textures.id = cosmetics.texture_id) as texture,
        (SELECT brands.name FROM brands WHERE brands.id = cosmetics.brand_id) as brand,
        (SELECT formats.format FROM formats WHERE formats.id = cosmetics.format_id) as format
        FROM products INNER JOIN cosmetics ON cosmetics.product_id = products.id WHERE product_id = ?', [$cosmetic])[0];

        $areas = DB::select('SELECT * FROM areas');
        $brands = DB::select('SELECT * FROM brands');
        $formats = DB::select('SELECT * FROM formats');
        $spfs = DB::select('SELECT * FROM spfs');
        $textures = DB::select('SELECT * FROM textures');



        return view('admin.cosmetic.edit', [
            'product'=>$product,
            'areas'=>$areas,
            'brands'=>$brands,
            'formats'=>$formats,
            'spfs'=>$spfs,
            'textures'=>$textures,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cosmetic $cosmetic)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        // dd($cosmetic->product_id);
        if ($request->all()['text']==null){
            $text=$request->all()['oldtext'];
        } else {
            $text = $request->all()['text'];
        }

        if ($request->all()['title'] == null || $request->all()['price'] == null || $request->all()['volume'] == null || $request->all()['count'] == null){
            return Redirect()->back()->withSuccess("Заполните все поля");
        }


        if ($request->all()['price'] <= 0){
            return Redirect()->back()->withSuccess("Некорректная цена");
        }

        if ($request->all()['volume'] <= 0){
            return Redirect()->back()->withSuccess("Некорректный объем");
        }

        if ($request->all()['count'] <= 0){
            return Redirect()->back()->withSuccess("Некорректное количество");
        }

        if (empty($request->all()['img'])){
            DB::update('UPDATE `products` SET `name` = ?, `price` = ?, `description` = ?, `count` = ? WHERE `products`.`id` = ?', [$request->all()['title'], $request->all()['price'], $text, $request->all()['count'], $cosmetic->product_id]);
        } else {
            unlink($_SERVER["DOCUMENT_ROOT"] . "/products/" . $request->all()['oldphoto']);

            // dd($request->all()['oldphoto']);

            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);
    
            $photo = $permitted_chars . '_' . $_FILES['img']['name'];

            DB::update('UPDATE `products` SET `name` = ?, `price` = ?, `description` = ?, `count` = ?, `photo` = ? WHERE `products`.`id` = ?', [$request->all()['title'], $request->all()['price'], $text, $request->all()['count'], $photo, $cosmetic->product_id]);
        
        
            if (!empty($_FILES)){
                move_uploaded_file(
                    $_FILES['img']['tmp_name'],
                    'products/' . $permitted_chars . '_' . $_FILES['img']['name']
                );
            }
        }

        DB::update('UPDATE `cosmetics` SET `area_id` = ?, `spf_id` = ?, `texture_id` = ?, `brand_id` = ?, `format_id` = ?, `volume` = ? WHERE `cosmetics`.`id` = ?', [$request->all()['area'], $request->all()['spf'], $request->all()['texture'], $request->all()['brand'], $request->all()['format'], $request->all()['volume'], $cosmetic->id]);

        return Redirect('/admin/cosmetics/'.$cosmetic->product_id)->withSuccess("Товар был успешно обновлен");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cosmetic $cosmetic)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        dd($cosmetic->product_id);
    }
}
