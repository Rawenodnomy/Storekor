<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $video = DB::select('SELECT video FROM `company`')[0]->video;
        return view('admin.video.index', [
            'video'=>$video
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
    public function show(string $id)
    {
        //
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
    public function update(Request $request, Company $company)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }

        unlink($_SERVER["DOCUMENT_ROOT"]."/videos/".$request->all()['old']);

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);

        $new = $permitted_chars . '_' . $_FILES['new']['name'];
        
        DB::update('UPDATE `company` SET `video` = ? WHERE `company`.`id` = 1', [$new]);
        
        if (!empty($_FILES)){
            move_uploaded_file(
                $_FILES['new']['tmp_name'],
                'videos/' . $permitted_chars . '_' . $_FILES['new']['name']
            );
        }
        


        
        return redirect()->back()->withSuccess(' Видео успешно обновлено');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
