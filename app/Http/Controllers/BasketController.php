<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Order;
use App\Models\Order_products;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BasketController extends Controller
{
    public function add (Request $request){
        $data = $request->validate([
            'vers'=>'',
        ]);
        $user = Auth::user();

        $product = Basket::where([
            ['position_id', '=', $request->id], ['user_id', '=', $user->id]
        ])->first();

        if($data['vers']){
            $product = Basket::where([
                ['position_id', '=', $request->id], ['user_id', '=', $user->id], ['version_id', '=', $data['vers']]
            ])->first();
        }
        // var_dump(Basket::find(1));
        if($product){
            $product->count = $product->count +1;
            $product->save();

        }
        else{
            if($data['vers']){
                $product = Basket::create([
                    'position_id'=>$request->id,
                    'user_id'=>$user->id,
                    'version_id'=>$data['vers'],
                    'count'=>1,
                ]);
            }
            else{
                $product = Basket::create([
                    'position_id'=>$request->id,
                    'user_id'=>$user->id,
                    'version_id'=>null,
                    'count'=>1,
                ]);
            }

        }
        $product->basketVersion;
        return response()->json(['count'=>$product->count, 'position'=>$request->id], 302 , ['Content-type'=>'application/json']);
    }
    public function minus (Request $request){
        $data = $request->validate([
            'vers'=>'',
        ]);

        $user = Auth::user();
        $product = Basket::where([
            ['position_id', '=', $request->id], ['user_id', '=', $user->id]
        ])->first();


        if($data['vers']){
            $product = Basket::where([
                ['position_id', '=', $request->id], ['user_id', '=', $user->id], ['version_id', '=', $data['vers']]
            ])->first();
        }
        // var_dump(Basket::find(1));
        if((int) $product->count -1 >0){
            $product->count = $product->count -1;
            $product->save();
            return response()->json(['count'=>$product->count, 'position'=>$request->id], 302 , ['Content-type'=>'application/json']);
        }
        else{
            $product->delete();
            return response()->json(['position'=>$request->id, 'deleted'=> true], 302 , ['Content-type'=>'application/json']);
        }

    }
    public function remove (Request $request){
        $data = $request->validate([
            'vers'=>'',
        ]);
        $user = Auth::user();
        $product = Basket::where([
            ['position_id', '=', $request->id], ['user_id', '=', $user->id]
        ])->first();
        if($data['vers']){
            $product = Basket::where([
                ['position_id', '=', $request->id], ['user_id', '=', $user->id], ['version_id', '=', $data['vers']]
            ])->first();
        }
        $product->delete();
        return response()->json(['position'=>$request->id, 'deleted'=> true],302, ['Content-type'=>'application/json']);
    }

    public function getUserBasket(){
        
        if (auth()->user()->is_blocked == 1){
            return redirect ('/');
        }
        $user = Auth::user();
        $contacts = DB::select('SELECT * FROM contacts WHERE id = ?', [$user->contacts_id]);
        $contacts=$contacts[0];
        $basket = Basket::where('user_id', '=', $user->id)->get();

        // dd($basket);

        foreach ($basket as $item){
            if ($item->version_id==null){
                
                $maxCount = DB::select('SELECT count FROM `products` WHERE id = ?', [$item->position_id])[0]->count;

                if ($maxCount<=$item->count){
                    $item->count = $maxCount;
                    DB::update('UPDATE `baskets` SET `count` = ? WHERE `baskets`.`id` = ?', [$maxCount,  $item->id]);
                }
                $item->maxCount= $maxCount;
            } else {
                
                $maxCount = DB::select('SELECT *, (SELECT albums.product_id FROM albums WHERE albums.id=versions.album_id) as prodid FROM `versions` HAVING prodid= ? AND id = ?', [$item->position_id, $item->version_id])[0]->count;

                if ($maxCount<=$item->count){
                    $item->count = $maxCount;
                    DB::update('UPDATE `baskets` SET `count` = ? WHERE `baskets`.`id` = ?', [$maxCount,  $item->id]);
                }
                $item->maxCount= $maxCount;
            }
            
        }
        session(['title'=>'Корзина']);

        return view('basket', compact('basket', 'contacts'));
    }

    public function addOrder(Request $request){
        date_default_timezone_set ('Asia/Yekaterinburg');
        // dd($request->all());
        // dd($request->validate['addres']);
        $data = $request->validate([
            'city'=>'required',
            'address'=>'required',
            'index_mail'=>'required',
            'FIO'=>'required',
        ]);



        $data['comment']=$request->all()['comment'];

        



        $user = Auth::user()->contacts_id;

        DB::update('UPDATE contacts SET city = ?, address = ?, index_mail = ?, FIO = ? WHERE id = ?', [$data['city'], $data['address'], $data['index_mail'], $data['FIO'], $user]);

        try {

            DB::transaction(function() use(&$data) {

                $user = Auth::user();

                $price= 0;
                $basket = Basket::where('user_id', '=', $user->id)->get();

                foreach($basket as $item){
                    $stok = DB::select('SELECT * FROM `stocks_by_types` WHERE ? between start and end AND type_id = ?', [date('Y/m/d'), $item->basketProduct->type_id]);
                    if ($stok !=[]){
                        $stok = $stok[0]->percent;
                    } else {
                        $stok = 0;
                    }


                    if ($item->version_id == NULL){
                        $product = DB::select('SELECT count FROM products WHERE id = ?', [$item->position_id]);


                        if ($product[0]->count >= $item->count){
                            $price+= $item->count * round(($item->basketProduct->price/100) * (100-$stok));
                            $count = $item->count;
                        } else {
                            $price+= $product[0]->count * round(($item->basketProduct->price/100) * (100-$stok));
                            $count = $product[0]->count;
                        }
                    } else {
                        $product = DB::select('SELECT count FROM versions WHERE id = ?', [$item->version_id]);
                        if ($product[0]->count >= $item->count){
                            $price+= $item->count * round(($item->basketProduct->price/100) * (100-$stok));
                            $count = $item->count;
                        } else {
                            $price+= $product[0]->count * round(($item->basketProduct->price/100) * (100-$stok));
                            $count = $product[0]->count;
                        }
                    }



                }

                $order = Order::create([
                    'user_id'=>$user->id,
                    'price'=>$price,
                    'status_id'=> 1,

                    'comment'=> $data['comment'],

                ]);

                foreach($basket as $item){
                    // dd($item->count);
                    if ($item->version_id==null){
                        $count = DB::select('SELECT count FROM `products` WHERE id = ?', [$item->position_id])[0]->count;
                        // dd($item->count, $count);
                        if ($item->count>$count){
                            $item->count=$count;
                        } 

                        $newCount = $count-$item->count;
                        // dd($item);
                        DB::update('UPDATE `products` SET `count` = ? WHERE `products`.`id` = ?', [$newCount, $item->position_id]);
                    } else {
                        $count = DB::select('SELECT count FROM `versions` WHERE id = ?', [$item->version_id])[0]->count;
                        // dd($count, $item->count);
                        if ($item->count>$count){
                            $item->count=$count;
                            // dd('больше чем есть');
                        } 
                        $newCount = $count-$item->count;
                        DB::update('UPDATE `versions` SET `count` = ? WHERE `versions`.`id` = ?', [$newCount, $item->version_id]);

                    }
                    Order_products::create([
                        'order_id'=>$order->id,
                        'product_id'=>$item->position_id,
                        'version_id'=>$item->version_id,
                        'count'=>$item->count,
                    ]);
                    $item->delete();
                }



            }, 3);  // Повторить три раза, прежде чем признать неудачу
            $id = Auth::user()->id;

            $whereid = DB::select('SELECT id FROM `orders` WHERE user_id = ? ORDER BY updated_at DESC LIMIT 1', [Auth::user()->id])[0]->id;

            return redirect('/getOrdersProduct/'.$whereid, 302);
        } catch (ErrorException $exception) {
            return redirect('/basket', 302)->withErrors(['oops'=>'Оформить заказ не удалось.']);
        }
    }
}

