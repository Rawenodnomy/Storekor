<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Version;

class VersionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $product = DB::select('SELECT id, name FROM products WHERE type_id = 3');
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }

        $versions = DB::select(('SELECT *, (SELECT albums.product_id FROM albums WHERE albums.id = versions.album_id) as product_id, (SELECT name FROM products WHERE id = product_id) as name FROM versions ORDER BY album_id DESC'));

        return view('admin.version.index', [
            'versions'=>$versions
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
        $products = DB::select('SELECT id, name FROM products WHERE type_id = 3');

        return view('admin.version.create', [
            'products'=>$products
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
        if ($request->all()['title']==null || $request->all()['count']==null){
            return redirect()->back()->withSuccess('Заполните все поля');
        }

        if ($request->all()['count']<0){
            return redirect()->back()->withSuccess('Некорректное количество');
        }

        $album_id = DB::select('SELECT id FROM albums WHERE product_id = ?', [$request->all()['product_id']]);
        $album_id=$album_id[0]->id;

        DB::insert('INSERT INTO `versions` (`id`, `album_id`, `version_name`, `count`) VALUES (NULL, ?, ?, ?)', [$album_id, $request->all()['title'], $request->all()['count']]);

        $id = DB::getPdo()->lastInsertId();
        return redirect('/admin/versions/'.$id)->withSuccess('Версия была успешно добавлена');

    }

    /**
     * Display the specified resource.
     */
    public function show(Version $version)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $vers = DB::select('SELECT *, (SELECT name FROM products WHERE albums.product_id = products.id) as album_name FROM albums WHERE id = ?', [$version->album_id]);
        $vers=$vers[0]->album_name;
        return view('admin.version.show', ['version'=>$version, 'vers'=>$vers]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Version $version)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }

        $vers = DB::select('SELECT *, (SELECT name FROM products WHERE albums.product_id = products.id) as album_name FROM albums WHERE id = ?', [$version->album_id]);
        $vers=$vers[0];
        $products = DB::select('SELECT id, name FROM products WHERE type_id = 3 AND id != ?', [$vers->product_id]);

        return view('admin.version.edit', ['version'=>$version, 'products'=>$products, 'vers'=>$vers]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Version $version)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        if ($request->all()['title']==null || $request->all()['count']==null){
            return redirect()->back()->withSuccess('Заполните все поля');
        }

        if ($request->all()['count']<0){
            return redirect()->back()->withSuccess('Некорректное количество');
        }

        $album_id = DB::select('SELECT id FROM albums WHERE product_id = ?', [$request->all()['product_id']]);
        $album_id=$album_id[0]->id;


        DB::update('UPDATE `versions` SET `album_id` = ?, `version_name` = ?, `count` = ? WHERE `versions`.`id` = ?', [$album_id, $request->all()['title'], $request->all()['count'], $version->id]);

        return redirect('/admin/versions/'.$version->id)->withSuccess('Версия была успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Version $version)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $version->delete();
        return redirect('/admin/versions')->withSuccess('Версия была успешно удалена');
    }
}
