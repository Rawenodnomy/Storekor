<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Group;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $groups = DB::select('SELECT *, (SELECT COUNT(*) FROM products WHERE products.group_id = groups.id) as count_product FROM `groups` ORDER BY id DESC');
        return view('admin.group.index', [
            'groups'=>$groups
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
        return view('admin.group.create', [
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
        if ($request->all()['title']==null || $request->all()['text']==null || empty($request->all()['img'])){
            return Redirect()->back()->withSuccess("Заполните все поля");
        }

        $rep = DB::select('SELECT * FROM `groups` WHERE `group_name` = ?', [$request->all()['title']]);

        if ($rep!=[]){
            return Redirect()->back()->withSuccess("Группа " . $request->all()['title'] . " уже существует");
        }
        
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);

        $photo = $permitted_chars . '_' . $_FILES['img']['name'];
        
        DB::insert('INSERT INTO `groups` (`id`, `group_name`, `photo_group`, `description`, `is_main`, `created_at`, `updated_at`) VALUES (NULL, ?, ?, ?, ?, NULL, NULL)', [$request->all()['title'], $photo, $request->all()['text'], $request->all()['main']]);

        if (!empty($_FILES)){
            move_uploaded_file(
                $_FILES['img']['tmp_name'],
                'groups/' . $permitted_chars . '_' . $_FILES['img']['name']
            );
        }

        return Redirect('/admin/groups')->withSuccess("Группа была успешно добавлена");
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        // dd($group->id);
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }

        $products = DB::select('SELECT *, (SELECT group_name FROM `groups` WHERE groups.id = products.group_id) as group_name, (SELECT type_products FROM types WHERE types.id = products.type_id) as type FROM products WHERE group_id = ?', [$group->id]);
        return view('admin.product.index', [
            'products'=>$products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        // dd($group);
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        return view('admin.group.edit', ['group'=>$group]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }

        if ($request->all()['title']==null){
            return Redirect()->back()->withSuccess("Заполните все название группы");
        }

        $rep = DB::select('SELECT * FROM `groups` WHERE `group_name` = ? AND id !=?', [$request->all()['title'], $request->id]);

        if ($rep!=[]){
            return Redirect()->back()->withSuccess("Группа " . $request->all()['title'] . " уже существует");
        }
     

        if ($request->all()['text'] == null){
            $text = $request->all()['oldtext'];
        } else {
            $text = $request->all()['text'];
        }


        if (empty($request->all()['img'])){
            DB::update('UPDATE `groups` SET `group_name` = ?, `description` = ?, `is_main` = ?, `created_at` = NULL, `updated_at` = NULL WHERE `groups`.`id` = ?', [$request->title, $text, $request->main, $request->id]);
        } else {
            $oldphotoname = DB::select('SELECT photo_group FROM `groups` WHERE id = ?', [$request->id])[0]->photo_group;
            unlink("groups/".$oldphotoname);


            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);
    
            $photo = $permitted_chars . '_' . $_FILES['img']['name'];

            DB::update('UPDATE `groups` SET `group_name` = ?, `description` = ?, `is_main` = ?, photo_group = ?, `created_at` = NULL, `updated_at` = NULL WHERE `groups`.`id` = ?', [$request->title, $text, $request->main, $photo, $request->id]);
            
            if (!empty($_FILES)){
                move_uploaded_file(
                    $_FILES['img']['tmp_name'],
                    'groups/' . $permitted_chars . '_' . $_FILES['img']['name']
                );
            }
        }

        return Redirect('/admin/groups')->withSuccess("Группа была успешно обновлена");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $photo = DB::select('SELECT photo_group FROM `groups` WHERE id = ?', [$group->id])[0]->photo_group;
        unlink("groups/".$photo);
        $group->delete();
        return redirect()->back()->withSuccess('Группа была успешно удалена');
    }
}
