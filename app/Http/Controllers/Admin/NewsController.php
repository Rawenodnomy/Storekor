<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $news = DB::select('SELECT * FROM `news`');

        return view('admin.new.index', [
            'news'=>$news
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
        return view('admin.new.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        // dd($request->all());
        if ($request->all()['title'] == null || $request->all()['text'] == null || empty($request->all()['img'])){
            return Redirect()->back()->withSuccess("Заполните все поля");
        }

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);

        $photo = $permitted_chars . '_' . $_FILES['img']['name'];

        DB::insert('INSERT INTO `news` (`id`, `title`, `content`, `image`, `created_at`) VALUES (NULL, ?, ?, ?, ?)', [$request->all()['title'], $request->all()['text'], $photo, date('Y-m-d')]);

        move_uploaded_file(
            $_FILES['img']['tmp_name'],
            'news/' . $permitted_chars . '_' . $_FILES['img']['name']
        );

        $id = DB::getPdo()->lastInsertId();

        return Redirect('/admin/news/'.$id)->withSuccess("Новость успешно добавлена");

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $new = DB::select('SELECT * FROM `news` WHERE id = ?', [$id])[0];

        return view('admin.new.show', [
            'new'=>$new
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
        $new = DB::select('SELECT * FROM `news` WHERE id = ?', [$id])[0];

        return view('admin.new.edit', [
            'new'=>$new
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        // dd($request->all(), $id);
        if ($request->all()['title']==null){
            return Redirect()->back()->withSuccess("Заполните заголовок");
        }


        if ($request->all()['newtext']==null){
            $text = $request->all()['oldtext'];
        } else {
            $text = $request->all()['newtext'];
        }

        DB::update('UPDATE `news` SET `title` = ?, `content` = ? WHERE `news`.`id` = ?', [$request->all()['title'], $text, $id]);


        if (!empty($request->all()['img'])){

            unlink($_SERVER["DOCUMENT_ROOT"] . "/news/" . $request->all()['oldphoto']);
            
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);
    
            $photo = $permitted_chars . '_' . $_FILES['img']['name'];

            DB::update('UPDATE `news` SET `image` = ? WHERE `news`.`id` = ?', [$photo, $id]);
            

            move_uploaded_file(
                $_FILES['img']['tmp_name'],
                'news/' . $permitted_chars . '_' . $_FILES['img']['name']
            );
        }

        return Redirect('/admin/news/'.$id)->withSuccess("Новость успешно обновлена");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $image = DB::select('SELECT image FROM `news` WHERE id = ?', [$id])[0]->image;

        unlink($_SERVER["DOCUMENT_ROOT"] . "/news/" . $image);

        DB::delete('DELETE FROM news WHERE `news`.`id` = ?', [$id]);
        
        return Redirect()->back()->withSuccess("Новость успешно удалена");
    }
}
