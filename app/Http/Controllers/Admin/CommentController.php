<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $id)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }

        $comments = DB::select('SELECT *, (SELECT name FROM products WHERE products.id = product_id) as product_name, (SELECT name FROM users WHERE id = user_id) as user_name FROM comments WHERE product_id = ? ORDER BY created_at DESC', [$id->id]);
        return view('admin.comment.index', [
            'comments'=>$comments
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
    public function show(Comment $comment)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $commentOne = DB::select('SELECT *, (SELECT name FROM products WHERE products.id = product_id) as product_name, (SELECT photo FROM products WHERE products.id = product_id) as product_photo, (SELECT name FROM users WHERE id = user_id) as user_name FROM comments WHERE id = ?', [$comment->id]);
        $commentOne = $commentOne[0];

        return view('admin.comment.show', [
            'comment'=>$commentOne,
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $fordel = DB::select('SELECT * FROM `comments` WHERE id = ?', [$comment->id])[0];

        if ($fordel->photo != null){
            unlink("comments/".$fordel->photo);
        }
        $comment->delete();

        return Redirect('/admin/users/'.$fordel->user_id."/edit")->withSuccess("Группа была успешно обновлена");

        // $comments = DB::select('SELECT *, (SELECT name FROM products WHERE products.id = product_id) as product_name, (SELECT name FROM users WHERE id = user_id) as user_name FROM comments WHERE product_id = ? ORDER BY created_at DESC', [$comment->product_id]);
        // return view('admin.comment.index', [
        //     'comments'=>$comments
        // ])->withSuccess('Комментарий был успешно удален');

    }
}
