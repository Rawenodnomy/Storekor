<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Information;

class InfoController extends Controller
{

    public function index()
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $infos = DB::select('SELECT * FROM information');
        return view('admin.info.index', [
            'infos'=>$infos
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
        return view('admin.info.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        if ($request->all()['title']==null || $request->all()['text']==null || empty($request->all()['img'])){
            return Redirect()->back()->withSuccess("Заполните все поля");
        } 

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);

        $photo = $permitted_chars . '_' . $_FILES['img']['name'];

        DB::insert('INSERT INTO `information` (`id`, `heading`, `img`, `text`) VALUES (NULL, ?, ?, ?)', [$request->all()['title'], $photo, $request->all()['text']]);
        
        if (!empty($_FILES)){
            move_uploaded_file(
                $_FILES['img']['tmp_name'],
                'info/' . $permitted_chars . '_' . $_FILES['img']['name']
            );
        }

        $id = DB::getPdo()->lastInsertId();

        return Redirect('/admin/infos/'.$id)->withSuccess("Инфоблок был успешно добавлен");

    }

    /**
     * Display the specified resource.
     */

    public function show(Information $info)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        return view('admin.info.show', ['info'=>$info]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Information $info)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        return view('admin.info.edit', ['info'=>$info]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Information $info)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }

        if ($request->all()['title'] == null){
            return redirect()->back()->withSuccess(' Заполните название инфоблока');
        }

        if ($request->text == NULL){
            $text = $request->oldtext;
        } else {
            $text = $request->text;
        }

        if (empty($request->all()['img'])){
            DB::update('UPDATE information SET `heading` = ?, `text` = ? WHERE `information`.`id` = ?', [$request->title, $text, $request->id]);
        }  else {
            $oldphotoname = DB::select('SELECT img FROM information WHERE id = ?', [$request->id])[0]->img;

            unlink("info/".$oldphotoname);

            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);
    
            $photo = $permitted_chars . '_' . $_FILES['img']['name'];

            DB::update('UPDATE information SET `heading` = ?, `text` = ?, img = ? WHERE `information`.`id` = ?', [$request->title, $text, $photo, $request->id]);

            if (!empty($_FILES)){
                move_uploaded_file(
                    $_FILES['img']['tmp_name'],
                    'info/' . $permitted_chars . '_' . $_FILES['img']['name']
                );
            }
        }

        return Redirect('/admin/infos/'.$request->id)->withSuccess("Инфоблок был успешно обновлен");
    }

    /**
     * Remove the specified resource from storage.
     */
    
    public function destroy(Information $info)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $photo = DB::select('SELECT img FROM `information` WHERE id = ?', [$info->id])[0]->img;
        unlink("info/".$photo);
        $info->delete();
        return redirect()->back()->withSuccess('Инфоблок был успешно удален');
    }
}

