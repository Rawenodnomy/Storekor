<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Type;

class TypesController extends Controller
{

    public function index()
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $types = DB::select('SELECT * FROM types ORDER BY id DESC');
        return view('admin.type.index', [
            'types'=>$types
        ]);
    }

    public function create()
    {
        // return view('admin.type.create', []);
    }

    public function store(Request $request)
    {
        // if ($request->all()['title']==null){
        //     return redirect()->back()->withSuccess('Заполните название типа');
        // }


        // DB::insert('INSERT INTO `types` (`type_products`) VALUES (?)', [$request->all()['title']]);

        // return redirect()->back()->withSuccess('Тип была успешно добавлен');
    }


    public function show(Type $type)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $products = DB::select('SELECT *, (SELECT group_name FROM `groups` 
        WHERE groups.id = products.group_id) as group_name, 
        (SELECT type_products FROM types WHERE types.id = products.type_id) as type FROM products 
        WHERE type_id = ?', [$type->id]);
        return view('admin.product.index', [
            'products'=>$products
        ]);
    }

    public function edit(Type $type)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        return view('admin.type.edit', ['type'=>$type]);
    }


    public function update(Request $request, Type $type)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        if ($request->all()['title']==null){
            return redirect()->back()->withSuccess('Заполните название типа');
        }

        $rep = DB::select('SELECT * FROM `types` WHERE type_products = ? AND id != ?', [$request->all()['title'], $request->id]);

        if ($rep !=[]){
            return redirect()->back()->withSuccess('Тип ' . $request->all()['title'] . ' уже существует');
        }

        DB::update('UPDATE `types` SET `type_products` = ? WHERE `types`.`id` = ?;', [$request->title, $request->id]);

        return redirect('/admin/types')->withSuccess('Тип был успешно обновлен');
    }

    public function destroy(Type $type)
    {
        // $type->delete();
        // return redirect()->back()->withSuccess('Тип был успешно удален');
    }
}
