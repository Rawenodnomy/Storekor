<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StocksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        date_default_timezone_set ('Asia/Yekaterinburg');
        $date = date('Y-m-d'); 
        $stocks = DB::select('SELECT *, (SELECT types.type_products FROM types WHERE types.id = stocks_by_types.type_id) as type FROM `stocks_by_types` ORDER BY end DESC');

        return view('admin.stock.index', [
            'stocks'=>$stocks,
            'date'=>$date
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
        $allTypes = DB::select('SELECT * FROM `types`');
        $types = [];
        date_default_timezone_set ('Asia/Yekaterinburg');
        
        $have = DB::select('SELECT * FROM `stocks_by_types` WHERE end >= ?', [date('Y-m-d')]);

        foreach($allTypes as $item){
            $rep=0;

            foreach ($have as $one){
                if ($one->type_id == $item->id){
                    $rep=$rep+1;
                }
            }

            if ($rep == 0){
                array_push($types, $item);
            }
        }




        $date = date('Y-m-d');

        $min = date('Y-m-d', strtotime($date . ' +1 day'));

        return view('admin.stock.create', [
            'types'=>$types,
            'date'=>$date,
            'min'=>$min            
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
        if ($request->all()['title']==null){
            return Redirect()->back()->withSuccess("Заполните заголвок акции");
        }

        if ($request->all()['percent']==null){
            return Redirect()->back()->withSuccess("Заполните процент скидки");
        }
        if ($request->all()['percent']<=0 || $request->all()['percent']>90){
            return Redirect()->back()->withSuccess("Некорректный процент скидки");
        }

        if ($request->all()['start']==null || $request->all()['end']==null){
            return Redirect()->back()->withSuccess("Заполните начало и окончание акции");
        }


        DB::insert('INSERT INTO `stocks_by_types` (`id`, `name`, `percent`, `type_id`, `start`, `end`) VALUES (NULL, ?, ?, ?, ?, ?)', [$request->all()['title'], $request->all()['percent'], $request->all()['type'], $request->all()['start'], $request->all()['end']]);

        return Redirect('/admin/stocks')->withSuccess("Акция была успешно добавлена");
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
    public function edit($id)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        $allTypes = DB::select('SELECT * FROM `types`');
        $types = [];
        date_default_timezone_set ('Asia/Yekaterinburg');
        
        $have = DB::select('SELECT * FROM `stocks_by_types` WHERE end >= ?', [date('Y-m-d')]);

        foreach($allTypes as $item){
            $rep=0;

            foreach ($have as $one){
                if ($one->type_id == $item->id){
                    $rep=$rep+1;
                }
            }

            if ($rep == 0){
                array_push($types, $item);
            }
        }

        $stock = DB::select('SELECT *, (SELECT types.type_products FROM types WHERE types.id = stocks_by_types.type_id) as type FROM `stocks_by_types` WHERE id = ?', [$id])[0];
        
        $date = date('Y-m-d');

        $min = date('Y-m-d', strtotime($date . ' +1 day'));
        
        return view('admin.stock.edit', [
            'stock'=>$stock,
            'types'=>$types,
            'date'=>$date,
            'min'=>$min            
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
        if ($request->all()['title']==null){
            return Redirect()->back()->withSuccess("Заполните заголвок акции");
        }

        if ($request->all()['percent']==null){
            return Redirect()->back()->withSuccess("Заполните процент скидки");
        }
        if ($request->all()['percent']<=0 || $request->all()['percent']>90){
            return Redirect()->back()->withSuccess("Некорректный процент скидки");
        }

        if ($request->all()['start']==null || $request->all()['end']==null){
            return Redirect()->back()->withSuccess("Заполните начало и окончание акции");
        }


        DB::update('UPDATE `stocks_by_types` SET `name` = ?, `percent` = ?, `type_id` = ?, `start` = ?, `end` = ? WHERE `stocks_by_types`.`id` = ?', [$request->all()['title'], $request->all()['percent'], $request->all()['type'], $request->all()['start'], $request->all()['end'], $id]);
        
        return Redirect('/admin/stocks')->withSuccess("Акция была успешно обновлена");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (auth()->user()==null || auth()->user()->is_admin!=1){
            return redirect('/');
        }
        DB::delete('DELETE FROM stocks_by_types WHERE `stocks_by_types`.`id` = ?', [$id]);
        return Redirect('/admin/stocks')->withSuccess("Акция была успешно удалена");
    }
}
