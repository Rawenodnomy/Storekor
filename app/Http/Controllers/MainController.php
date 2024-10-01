<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Comment;
use App\Models\position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class MainController extends Controller
{
    function index() {

        $company = DB::select('SELECT * FROM company')[0];
        $countProd = DB::select('SELECT COUNT(*) as count FROM products')[0];
        $countOrder = DB::select('SELECT COUNT(*) as count FROM orders')[0];
        $countUser = DB::select('SELECT COUNT(*) as count FROM users')[0];
        $countGroup = DB::select('SELECT COUNT(*) as count FROM `groups`')[0];

        date_default_timezone_set ('Asia/Yekaterinburg');

        $stocks = DB::select('SELECT *, (SELECT types.direction FROM types WHERE types.id = stocks_by_types.type_id) as dire FROM `stocks_by_types`  WHERE ? between start and end', [date('Y/m/d')]);


        $news = DB::select('SELECT * FROM news ORDER BY created_at DESC LIMIT 2');

        foreach ($news as $new){
            $new->content = substr($new->content, 0 , 80);
            $new->content = $new->content . '...';
        }

        $groups = DB::select('SELECT * FROM `groups` WHERE is_main = 1');

        $types = DB::select('SELECT * FROM types');
        $information = DB::select('SELECT * FROM information');

        $newProducts = [];

        foreach ($types as $type) {
            if ($type->id !=3){
                $query = DB::select('SELECT *, (SELECT groups.group_name FROM `groups` WHERE groups.id = products.group_id) as group_name FROM products WHERE type_id = ? ORDER BY products.created_at DESC LIMIT 6', [$type->id]);

                foreach ($query as $one){
                    $one->description = mb_strimwidth($one->description, 0, 90, "...");


                    if($one->type_id==10){
                        $category = DB::select('SELECT *, (SELECT food_categories.name FROM food_categories WHERE food_categories.id = foods.food_category_id) as category FROM `foods` WHERE product_id=?', [$one->id])[0]->category;
                        $one->category = $category;
                    }

                    if($one->type_id==11){
                        $category = DB::select('SELECT *, (SELECT textures.texture FROM textures WHERE textures.id = cosmetics.texture_id) as category FROM `cosmetics` WHERE product_id = ?', [$one->id])[0]->category;
                        $one->category = $category;
                    }
                    
                    
                    array_push($newProducts, $one);
                }
            } else {
                $query = DB::select('SELECT *, (SELECT albums.id FROM albums WHERE albums.product_id = products.id) as alb_id, (SELECT groups.group_name FROM `groups` WHERE groups.id = group_id) as group_name, (SELECT SUM(count) FROM versions WHERE versions.album_id = alb_id) as count_ver FROM products having count_ver >0 AND type_id=3 ORDER BY created_at DESC LIMIT 6');
                
                
                foreach ($query as $one){
                    $one->description = mb_strimwidth($one->description, 0, 90, "...");
                    
                    
                    array_push($newProducts, $one);
                }
            }

        }

        $month = [
            'Января',
            'Февраля',
            'Марта',
            'Апреля',
            'Мая',
            'Июня',
            'Июля',
            'Августа',
            'Сентября',
            'Октября',
            'Ноября',
            'Декабря'
          ];
          
        
        foreach ($stocks as $stock){
            $day = substr($stock->end, -2);
            if (substr($day, 0, 1) == 0){
                $day = substr($day, -1);
            }
            $stock->end = $day . ' ' . $month[substr($stock->end, 5,2)-1] . ' ' . substr($stock->end, 0,4);
        
            $day = substr($stock->start, -2);
            if (substr($day, 0, 1) == 0){
                $day = substr($day, -1);
            }
            $stock->start = $day . ' ' . $month[substr($stock->start, 5,2)-1] . ' ' . substr($stock->start, 0,4);
        
        
        }

        session(['title'=>'Главная страница']);


        return view("welcome", compact('groups', 'newProducts', 'information', 'types', 'countProd', 'countOrder', 'countUser', 'countGroup', 'company', 'stocks', 'news'));

    }

    public function getProductsByGroup($id, Request $request)
    {
        $types = DB::select('SELECT * FROM types WHERE direction = "kpop"');
        $group = DB::select('SELECT * FROM `groups` WHERE id = ?', [$id]);
        $countProdGroup = DB::select('SELECT COUNT(*) as count FROM `products` WHERE group_id = ?', [$id])[0]->count;
        if ($group !=[]){
            $group = $group[0];
        } else {
            $group = false;
        }
        $groups = Group::all();
        // $products = DB::select('SELECT *, (SELECT groups.group_name FROM `groups` WHERE groups.id = products.group_id) as group_name FROM products WHERE group_id = ?', [$id]);
        $products = position::where('group_id', '=', $id)->get();
        // dd($products);
        $i=0;
        for ($i = 0; $i < count($products); $i++) {
            $products->all()[$i]->position=$i+1;
        }
        session(['title'=>$group->group_name]);
        return view("catalog", compact('group', 'types' ,'products', 'groups', 'countProdGroup'));

    }

    public function getCatalog(Request $request)
    {

        // dd('da');
        $types = DB::select('SELECT * FROM types WHERE direction = "kpop"');
        // $products = position::all();
        $products = position::where('count', '>', 0)->where('type_id', '=',  4)->get();

        $albums = position::where('type_id', '=',  3)->get();

        $products = $products->merge($albums); 

        // $ids = DB::select('SELECT *, (SELECT albums.id FROM albums WHERE albums.product_id = products.id) as alb_id, (SELECT SUM(count) FROM versions WHERE versions.album_id = alb_id) as count_ver FROM products having count_ver =0 AND type_id=3');


        $group = false;
        $groups = Group::all();

        
        // dd(count($products));
        $i=0;
        for ($i = 0; $i < count($products); $i++) {
            $products->all()[$i]->position=$i+1;
        }
        
        session(['title'=>'K-pop Каталог']);

        return view("catalog", compact('group', 'types' ,'products', 'groups'));
    }



    public function getsearch(){
        $prods = [];

        $products = position::all();

        foreach ($products as $prod){
            // 
            if ($prod->type_id ==3){
                $alb = DB::select('SELECT *, (SELECT albums.product_id FROM albums WHERE albums.id=versions.album_id) as prodid FROM `versions` HAVING prodid=?', [$prod->id]);
                // dd($alb);
                $count = 0;
                foreach ($alb as $item){
                    $count = $count + $item->count;
                }
                $prod->count = $count;
            } 

            if ($prod->count>0){
                array_push($prods, $prod);
            }

            if ($prod->type_id==3 || $prod->type_id==4){
                $prod->dop=DB::select('SELECT * FROM `products` INNER JOIN `groups` ON groups.id = products.group_id WHERE products.id = ?',[$prod->id])[0]->group_name;
            } elseif ($prod->type_id==10) {
                $prod->dop=DB::select('SELECT *, (SELECT name FROM `food_categories` WHERE id = (SELECT `food_category_id` FROM `foods` WHERE `product_id` = products.id)) as cat FROM `products` WHERE id = ?',[$prod->id])[0]->cat;
            } elseif ($prod->type_id==11){
                $prod->dop=DB::select('SELECT *, (SELECT name FROM `brands` WHERE id = (SELECT brand_id FROM `cosmetics` WHERE product_id = products.id)) as brand FROM `products` WHERE id = ?',[$prod->id])[0]->brand;
            }

        }
        return $prods;
    }

    public function getAsyncCatalog(Request $request){
        $data = $request->validate([
            'group'=>'',
            'type'=>'',
            'orders'=>'',
        ]);
        // var_dump($data);
        $products = [];
        if($data['group'] && $data['type']){
            if($data['orders']['hovizn']['value'] !=0 && $data['orders']['price']['value'] !=0 ){
                $roderPri = '';
                if($data['orders']['price']['value'] ==1){
                    $roderPri = 'asc';
                }
                if($data['orders']['price']['value'] ==-1){
                    $roderPri = 'desc';
                }
                $roderNow = '';
                if($data['orders']['hovizn']['value'] ==1){
                    $roderNow = 'asc';
                }
                if($data['orders']['hovizn']['value'] ==-1){
                    $roderNow = 'desc';
                }
                if($data['orders']['hovizn']['number'] > $data['orders']['price']['number']){
                    $products = position::where([['group_id', '=', $data['group']], ['type_id', '=', $data['type']]])->whereIn('type_id', [3,4])->orderBy('created_at', $roderNow)->orderBy('price', $roderPri)->get();
                }
                else{
                    $products = position::where([['group_id', '=', $data['group']], ['type_id', '=', $data['type']]])->whereIn('type_id', [3,4])->orderBy('price', $roderPri)->orderBy('created_at', $roderNow)->get();
                }
            }
            elseif($data['orders']['price']['value'] !=0){
                $roder = '';
                if($data['orders']['price']['value'] ==1){
                    $roder = 'asc';
                }
                if($data['orders']['price']['value'] ==-1){
                    $roder = 'desc';
                }
                $products = position::where([['group_id', '=', $data['group']], ['type_id', '=', $data['type']]])->whereIn('type_id', [3,4])->orderBy('price', $roder)->get();
            }
            elseif ($data['orders']['hovizn']['value'] !=0) {
                $roder = '';
                if($data['orders']['hovizn']['value'] ==1){
                    $roder = 'asc';
                }
                if($data['orders']['hovizn']['value'] ==-1){
                    $roder = 'desc';
                }
                $products = position::where([['group_id', '=', $data['group']], ['type_id', '=', $data['type']]])->whereIn('type_id', [3,4])->orderBy('created_at', $roder)->get();
            }
            else{
                $products =  position::where([['group_id', '=', $data['group']], ['type_id', '=', $data['type']]])->whereIn('type_id', [3,4])->get();
            }
        }
        elseif($data['group']){
            if($data['orders']['hovizn']['value'] !=0 && $data['orders']['price']['value'] !=0 ){
                $roderPri = '';
                if($data['orders']['price']['value'] ==1){
                    $roderPri = 'asc';
                }
                if($data['orders']['price']['value'] ==-1){
                    $roderPri = 'desc';
                }
                $roderNow = '';
                if($data['orders']['hovizn']['value'] ==1){
                    $roderNow = 'asc';
                }
                if($data['orders']['hovizn']['value'] ==-1){
                    $roderNow = 'desc';
                }
                if($data['orders']['hovizn']['number'] > $data['orders']['price']['number']){
                    $products = position::where('group_id', '=', $data['group'])->whereIn('type_id', [3,4])->orderBy('created_at', $roderNow)->orderBy('price', $roderPri)->get();
                }
                else{
                    $products = position::where('group_id', '=', $data['group'])->whereIn('type_id', [3,4])->orderBy('price', $roderPri)->orderBy('created_at', $roderNow)->get();
                }
            }
            elseif($data['orders']['price']['value'] !=0){
                $roder = '';
                if($data['orders']['price']['value'] ==1){
                    $roder = 'asc';
                }
                if($data['orders']['price']['value'] ==-1){
                    $roder = 'desc';
                }
                $products = position::where('group_id', '=', $data['group'])->whereIn('type_id', [3,4])->orderBy('price', $roder)->get();
            }
            elseif ($data['orders']['hovizn']['value'] !=0) {
                $roder = '';
                if($data['orders']['hovizn']['value'] ==1){
                    $roder = 'asc';
                }
                if($data['orders']['hovizn']['value'] ==-1){
                    $roder = 'desc';
                }
                $products = position::where('group_id', '=', $data['group'])->whereIn('type_id', [3,4])->orderBy('created_at', $roder)->get();
            }
            else{
                $products = position::where('group_id', '=', $data['group'])->whereIn('type_id', [3,4])->get();
            }
        }
        elseif($data['type']){
            if($data['orders']['hovizn']['value'] !=0 && $data['orders']['price']['value'] !=0 ){
                $roderPri = '';
                if($data['orders']['price']['value'] ==1){
                    $roderPri = 'asc';
                }
                if($data['orders']['price']['value'] ==-1){
                    $roderPri = 'desc';
                }
                $roderNow = '';
                if($data['orders']['hovizn']['value'] ==1){
                    $roderNow = 'asc';
                }
                if($data['orders']['hovizn']['value'] ==-1){
                    $roderNow = 'desc';
                }
                if($data['orders']['hovizn']['number'] > $data['orders']['price']['number']){
                    $products = position::where('type_id', '=', $data['type'])->whereIn('type_id', [3,4])->orderBy('created_at', $roderNow)->orderBy('price', $roderPri)->get();
                }
                else{
                    $products = position::where('type_id', '=', $data['type'])->whereIn('type_id', [3,4])->orderBy('price', $roderPri)->orderBy('created_at', $roderNow)->get();
                }
            }
            elseif($data['orders']['price']['value'] !=0){
                $roder = '';
                if($data['orders']['price']['value'] ==1){
                    $roder = 'asc';
                }
                if($data['orders']['price']['value'] ==-1){
                    $roder = 'desc';
                }
                $products = position::where('type_id', '=', $data['type'])->whereIn('type_id', [3,4])->orderBy('price', $roder)->get();
            }
            elseif ($data['orders']['hovizn']['value'] !=0) {
                $roder = '';
                if($data['orders']['hovizn']['value'] ==1){
                    $roder = 'asc';
                }
                if($data['orders']['hovizn']['value'] ==-1){
                    $roder = 'desc';
                }
                $products = position::where('type_id', '=', $data['type'])->whereIn('type_id', [3,4])->orderBy('created_at', $roder)->get();
            }
            else{
                $products = position::where('type_id', '=', $data['type'])->whereIn('type_id', [3,4])->get();
            }
        }
        else{
            if($data['orders']['hovizn']['value'] !=0 && $data['orders']['price']['value'] !=0 ){
                $roderPri = '';
                if($data['orders']['price']['value'] ==1){
                    $roderPri = 'asc';
                }
                if($data['orders']['price']['value'] ==-1){
                    $roderPri = 'desc';
                }
                $roderNow = '';
                if($data['orders']['hovizn']['value'] ==1){
                    $roderNow = 'asc';
                }
                if($data['orders']['hovizn']['value'] ==-1){
                    $roderNow = 'desc';
                }
                if($data['orders']['hovizn']['number'] > $data['orders']['price']['number']){
                    $products = position::orderBy('created_at', $roderNow)->whereIn('type_id', [3,4])->orderBy('price', $roderPri)->get();
                }
                else{
                    $products = position::orderBy('price', $roderPri)->whereIn('type_id', [3,4])->orderBy('created_at', $roderNow)->get();
                }
            }
            elseif($data['orders']['price']['value'] !=0){
                $roder = '';
                if($data['orders']['price']['value'] ==1){
                    $roder = 'asc';
                }
                if($data['orders']['price']['value'] ==-1){
                    $roder = 'desc';
                }
                $products = position::orderBy('price', $roder)->whereIn('type_id', [3,4])->get();
            }
            elseif ($data['orders']['hovizn']['value'] !=0) {
                $roder = '';
                if($data['orders']['hovizn']['value'] ==1){
                    $roder = 'asc';
                }
                if($data['orders']['hovizn']['value'] ==-1){
                    $roder = 'desc';
                }
                $products = position::orderBy('created_at', $roder)->whereIn('type_id', [3,4])->get();
            }
            else{
                $products = position::whereIn('type_id', [3,4])->get();
            }
        }
        foreach($products as $product){
            $product->productGroup;
            $product->productCategory;
            if($product->productAlbums){
                $product->productAlbums->albumVersion;
            }
        }
        
        return response()->json($products, 302);
    }



    public function getInfo ($id)
    {
        $information = DB::select('SELECT * FROM information');
        $what = $id;
        foreach ($information as $item){
            if ($item->id==$what){
                session(['title'=>$item->heading]);
            }
        }
        return view("info", compact('information', 'what'));

    }

    public function showProduct($id)
    {
        date_default_timezone_set ('Asia/Yekaterinburg');
        if (Auth::user()){
            $fav = DB::select('SELECT * FROM `favourites` WHERE user_id = ? AND product_id = ?', [Auth::user()->id, $id]);
            if ($fav != []){
                $fav = false;
            } else {
                $fav = true;
            }
        } else{
            $fav = false;
        }


        session()->push('recently', $id);
        $recently = session()->get('recently');
        
        
        if (count($recently)>5){
            array_shift($recently);
            session(['recently' => $recently]);
        }
        

        $product = DB::select('SELECT *, (SELECT groups.group_name FROM `groups` WHERE groups.id = products.group_id) as group_name FROM products WHERE id=?', [$id]);
        $product = $product[0];
        $stok = DB::select('SELECT * FROM `stocks_by_types` WHERE ? between start and end AND type_id = ?', [date('Y/m/d'), $product->type_id]);
        if ($stok !=[]){
            $stok = $stok[0];
        } else {
            $stok = false;
        }


        if ($product->type_id == 3){
            $idAl = DB::select('SELECT *, (SELECT albums.id FROM albums WHERE albums.product_id = ?) as albums FROM versions LIMIT 1', [$product->id]);
            $versions = DB::select('SELECT * FROM versions WHERE album_id = ? AND count >0', [$idAl[0]->albums]);
        } else {
            $versions=[];
        }

        $comments = DB::select('SELECT *, (SELECT name FROM users WHERE comments.user_id = users.id) as user_name, (SELECT avatar FROM users WHERE users.id = comments.user_id) as user_avatar FROM comments WHERE product_id = ? ORDER BY id DESC', [$id]);
        // $comments = Comment::select('comments.*', 'users.name as user_name', 'users.avatar as user_avatar')
        // ->join('users', 'comments.user_id', '=', 'users.id')
        // ->where('product_id', $id);
        // ->paginate(100);
        // $comments = Comment::paginate(3);

        


        $month = [
            'Января',
            'Февраля',
            'Марта',
            'Апреля',
            'Мая',
            'Июня',
            'Июля',
            'Августа',
            'Сентября',
            'Октября',
            'Ноября',
            'Декабря'
          ];


        foreach ($comments as $com){
            $day = substr($com->created_at, -2);

            if (substr($day, 0, 1) == 0){
                $day = substr($day, -1);
            }
    
    
            $com->created_at = $day . ' ' . $month[substr($com->created_at, 5,2)-1] . ' ' . substr($com->created_at, 0,4);
        }




        

        if ($product->group_id != null){
            $otherProducts = DB::select('SELECT *, (SELECT albums.id FROM albums WHERE albums.product_id = products.id) as alb_id, (SELECT groups.group_name FROM `groups` WHERE groups.id = products.group_id) as group_name, (SELECT SUM(count) FROM versions WHERE versions.album_id = alb_id) as count_ver FROM products having group_id = ? AND id !=? AND (count_ver >0 OR count>0) ORDER BY created_at DESC LIMIT 5', [$product->group_id, $product->id]);
        } 

        if ($product->type_id == 11){
            $cosmetic = DB::select('SELECT *, 
            (SELECT areas.zone FROM areas WHERE areas.id = area_id) as area,
            (SELECT spfs.spf FROM spfs WHERE spfs.id = spf_id) as spf,
            (SELECT textures.texture FROM textures WHERE textures.id = texture_id) as texture,
            (SELECT brands.name FROM brands WHERE brands.id = brand_id) as brand,
            (SELECT formats.format FROM formats WHERE formats.id = format_id) as format
            FROM `cosmetics` WHERE product_id = ?', [$id])[0];

            $otherProducts = [];

            $prodby = DB::select('SELECT *, (SELECT count FROM products WHERE products.id = product_id) as count FROM `cosmetics` HAVING count>0 AND texture_id = ? AND product_id != ? LIMIT 5', [$cosmetic->texture_id, $cosmetic->product_id]);
            
            foreach ($prodby as $item){
                $one = DB::select('SELECT *, 
                (SELECT brands.name FROM brands WHERE brands.id = (SELECT cosmetics.brand_id FROM cosmetics WHERE product_id = products.id)) as brand,
                (SELECT textures.texture FROM textures WHERE textures.id = (SELECT cosmetics.texture_id FROM cosmetics WHERE product_id = products.id)) as texture
                FROM products WHERE id = ?', [$item->product_id])[0];
                $one->type = $one->brand . ' - ' . $one->texture;
                array_push($otherProducts, $one);
            }
        } else {
            $cosmetic = false;
        }


        if ($product->type_id == 10){
            
            $food = DB::select('SELECT *, (SELECT food_categories.name FROM food_categories WHERE food_categories.id = food_category_id) as cat FROM `foods` WHERE product_id = ?', [$product->id])[0];
            $otherProducts = [];
            $prodBy = DB::select('SELECT *, (SELECT count FROM products WHERE products.id = product_id) as count FROM `foods` HAVING food_category_id = ? AND count>0 AND product_id !=?', [$food->food_category_id, $food->product_id]);

            foreach ($prodBy as $item){
                $one = DB::select('SELECT *, (SELECT food_categories.name FROM food_categories WHERE food_categories.id = (SELECT foods.food_category_id FROM foods WHERE foods.product_id = products.id)) as type FROM `products` WHERE id = ?', [$item->product_id])[0];
                array_push($otherProducts, $one);
            }
        } else {
            $food = false;
        }

        // $two = Comment::where('product_id', '=',  $id)->orderBy('created_at', 'DESC')->get()->paginate(1);
        // $products = Product::;
        
        
        // dd($comments);

        // dd($product);

        if ($product->type_id==3){
            $count = DB::select('SELECT *, (SELECT albums.product_id FROM albums WHERE albums.id = versions.album_id) as prodid FROM `versions` HAVING prodid = ?', [$product->id]);
            $sum = 0;
            foreach ($count as $item){
                $sum = $sum + $item->count;
            }
            $product->count = $sum;
        }



        for ($i = 0; $i < count($comments); $i++) {
            $comments[$i]->position=$i+1;
        }
        session(['title'=>$product->name]);

        return view("show", compact('product', 'versions', 'comments', 'otherProducts', 'fav', 'stok', 'cosmetic', 'food'));

    }


    public function getProductsByType ($id) {
        // $products = DB::select('SELECT * FROM products WHERE type_id = ?', [$id]);
        $types = DB::select('SELECT * FROM types WHERE direction = "kpop"');
        // $products = DB::select('SELECT *, (SELECT groups.group_name FROM `groups` WHERE groups.id = products.group_id) as group_name FROM products');

        $products = position::where('type_id', '=', $id)->get();

        $group = false;
        $groups = Group::all();
        
        foreach ($types as $item){
            if ($item->id==$id){
                session(['title'=>$item->type_products]);
            }
        }
        
        return view("catalog", compact('group', 'types' ,'products', 'groups' , 'id'));

    }

    public function getGroups () {
        $what = 'Группы';
        $groups = DB::select('SELECT * FROM `groups` ORDER BY group_name ');
        session(['title'=>'Группы']);

        return view("groups", compact('groups', 'what'));
    }

    public function deleteComment(Request $request) {


        DB::delete('DELETE FROM comments WHERE id = ?', [$request->all()['comment_id']]);

        
        return redirect ('/showProduct/' . $request->all()['product'] . '#' . 'comments');
     
        

    }



    public function createComment(Request $request) {

        // dd($request->all());

        if(empty($request->all()['image'])){
            $photo = null;
        } else {
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);
    
            $photo = $permitted_chars . '_' . $_FILES['image']['name'];


        }


        $badwords = ["хуй","пизд","далбо","долбо","уеб","хуе","хуя","пидо","пидр","оху","аху","залу","пезд","еба","еб", "бля", 'морковь', 'терроризм', 'террор', 'война', 'суицид'];
        $pattern = "/\b[а-яё]*(".implode("|", $badwords).")[а-яё]*\b/ui";
        $res = preg_replace($pattern, "***", $request->all()['content']);

        if ($request->all()['content'] !=null){
            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                'comments/' . $photo
            );

            DB::insert('INSERT INTO `comments` (`id`, `user_id`, `content`, `photo`, `product_id`, `created_at`, `updated_at`) VALUES (NULL, ?, ?, ?, ?, ?, NULL);', [$request->all()['user_id'], $res, $photo, $request->all()['product_id'], date('Y-m-d')]);
        }

        return redirect ('/showProduct/' . $request->all()['product_id'] . '#' . DB::getPdo()->lastInsertId());
    }


    public function editProfile() {

        if (Auth::user()){
            $user = DB::select('SELECT * FROM users WHERE id = ?', [Auth::user()->id]);
            $user=$user[0];
            session(['title'=>'Редактировать профиль']);
            return view("editProfile", compact('user'));
        } else{
            return redirect('/', 302);
        }


    }

    public function saveEditProfile(Request $request) {

        $repemail = DB::select('SELECT * FROM users WHERE email = ? AND id !=?', [$request->all()['email'], auth()->user()->id]);
        $repname = DB::select('SELECT * FROM users WHERE name = ? AND id !=?', [$request->all()['name'], auth()->user()->id]);

        if ($repemail!=[]){
            return Redirect()->back()->withSuccess("Почта " . $request->all()['email'] . " занята");
        }
        
        if ($repname!=[]){
            return Redirect()->back()->withSuccess("Логин " . $request->all()['name'] . " занят");
        }

        if (empty($request->all()['img'])){
            DB::update('UPDATE users SET name = ?, email = ?, email_verified_at = NULL WHERE users.id = ?', [$request->all()['name'], $request->all()['email'], auth()->user()->id]);
        } else{
            $oldphotoname = DB::select('SELECT avatar FROM users WHERE id = ?', [auth()->user()->id])[0]->avatar;

            if ($oldphotoname!='def.jpg'){
                unlink("users/".$oldphotoname);
            } 

            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $permitted_chars = substr(str_shuffle($permitted_chars), 0, 10);
    
            $photo = $permitted_chars . '_' . $_FILES['img']['name'];

            DB::update('UPDATE users SET avatar = ?, name = ?, email = ?, email_verified_at = NULL WHERE users.id = ?', [$photo, $request->all()['name'], $request->all()['email'], auth()->user()->id]);

            if (!empty($_FILES)){
                move_uploaded_file(
                    $_FILES['img']['tmp_name'],
                    'users/' . $permitted_chars . '_' . $_FILES['img']['name']
                );
            }
        }

        
        return redirect('/editProfile', 302);

    }


    public function getContact() {
        
        if (Auth::user()){

                $contact = DB::select('SELECT * FROM contacts WHERE id = ?', [Auth::user()->contacts_id]);

                $contact = $contact[0];
        
                $user_id = Auth::user()->id;
                
                session(['title'=>'Данные для доставки']);
        
                return view("contact", compact('contact', 'user_id'));

        } else {
            return redirect('/', 302);
        }


    }


    public function saveContacts(Request $request) {


        DB::update('UPDATE contacts SET city = ?, address = ?, FIO = ?, index_mail = ? WHERE contacts.id = ?', [$request->all()['city'], $request->all()['address'], $request->all()['FIO'], $request->all()['index_mail'], $request->all()['id']]);


        return redirect('/getContact', 302);
    }

    public function getOrdersByUser(){
        
        
        if (Auth::user()){
            $orders = DB::select('SELECT *, (SELECT stage FROM statuses WHERE id = orders.status_id) as stage FROM orders WHERE user_id = ? ORDER BY updated_at DESC', [Auth::user()->id]);
            
            for ($i = 0; $i < count($orders); $i++) {
                $orders[$i]->position=$i+1;
            }

            $month = [
                'Января',
                'Февраля',
                'Марта',
                'Апреля',
                'Мая',
                'Июня',
                'Июля',
                'Августа',
                'Сентября',
                'Октября',
                'Ноября',
                'Декабря'
              ];
    
    
            foreach ($orders as $ord){
                $day = substr($ord->created_at, -2);
    
                if (substr($day, 0, 1) == 0){
                    $day = substr($day, -1);
                }
                $ord->created_at = $day . ' ' . $month[substr($ord->created_at, 5,2)-1] . ' ' . substr($ord->created_at, 0,4);

            }

            session(['title'=>'Ваши заказы']);

            return view("orders", compact('orders'));
        } else{
            return redirect('/', 302);
        }

    }

    public function getOrdersProduct($id){
       
        date_default_timezone_set ('Asia/Yekaterinburg');

        if (Auth::user()){
            $order_product = DB::select('SELECT *, order_products.count as count_order, (SELECT type_products FROM types WHERE types.id = type_id) as type,  (SELECT version_name FROM versions WHERE versions.id = version_id) as version_name, (SELECT group_name FROM `groups` WHERE groups.id = group_id) as group_name FROM order_products INNER JOIN products ON products.id = order_products.product_id WHERE order_id = ?', [$id]);
            $order = DB::select('SELECT *, (SELECT SUM(count) FROM order_products WHERE order_products.order_id = orders.id) as count FROM orders WHERE id = ?', [$id])[0];
            
            $date = $order->created_at;
            $date = substr($date, 0, 4) . '/' . substr($date, 5, 2) . '/' . substr($date, 8, 2);

            foreach ($order_product as $item){
                $proc = DB::select('SELECT * FROM `stocks_by_types` WHERE ? between start and end AND type_id = ?', [$date, $item->type_id]);
                if ($proc!=[]){
                    $item->price = round(($item->price/100)*(100-$proc[0]->percent));
                }
            }



            $month = [
                'Января',
                'Февраля',
                'Марта',
                'Апреля',
                'Мая',
                'Июня',
                'Июля',
                'Августа',
                'Сентября',
                'Октября',
                'Ноября',
                'Декабря'
              ];
    
    

                $day = substr($order->created_at, -2);
    
                if (substr($day, 0, 1) == 0){
                    $day = substr($day, -1);
                }
                $order->created_at = $day . ' ' . $month[substr($order->created_at, 5,2)-1] . ' ' . substr($order->created_at, 0,4);


            if (!empty($order_product)){
                $user_id = DB::select('SELECT user_id FROM orders WHERE id = ?', [$id])[0]->user_id;
                if (Auth::user()->id==$user_id){
                    session(['title'=>'Товары в заказе']);
                    return view("orderProducts", compact('order_product', 'order'));
                } else {
                    return redirect('/', 302);
                }

            } else {
                return redirect('/', 302);
            }

        } else{
            return redirect('/', 302);
        }


    }



    public function search(Request $request){

       
        

        $string = $request->all()['search'];
        $words = explode(" ", $string);
        $search = '';



        $products = [];
        $ids = [];
        
        foreach ($words as $word){
            $search = $search . $word . ' ';

            $word = '%' . $word . '%';

            $one = DB::select('SELECT *, 
            (SELECT groups.group_name FROM `groups` WHERE groups.id = products.group_id) as `group`,
            
            (SELECT brands.name FROM brands WHERE brands.id = (SELECT cosmetics.brand_id FROM cosmetics WHERE product_id = products.id)) as brand,
            (SELECT textures.texture FROM textures WHERE textures.id = (SELECT cosmetics.texture_id FROM cosmetics WHERE product_id = products.id)) as texture,
            (SELECT food_categories.name FROM food_categories WHERE food_categories.id = (SELECT foods.food_category_id FROM foods WHERE foods.product_id = products.id)) as type,
            (SELECT SUM(versions.count) FROM versions WHERE versions.album_id = (SELECT albums.id FROM albums WHERE albums.product_id = products.id)) as alb_count
            FROM `products` HAVING name LIKE ? OR `group` LIKE ? OR description LIKE ? OR brand LIKE ? OR texture LIKE ? OR type LIKE ?', [$word, $word, $word, $word, $word, $word]);

            foreach ($one as $item){
                if ($item->type_id==3){
                    if ($item->alb_count > 0){
                        $rep = 0;
                        foreach ($ids as $id){
                            if ($id==$item->id){
                                $rep = $rep+1;
                            }
                        }
                        if ($rep==0){
                            $item->cat = $item->group;
                            array_push($products, $item);
                            array_push($ids, $item->id);
                        }
                    }
                } else{
                    if ($item->count > 0){
                        $rep = 0;
                        foreach ($ids as $id){
                            if ($id==$item->id){
                                $rep = $rep+1;
                            }
                        }
                        if ($rep==0){
                            if ($item->type_id == 4){
                                $item->cat = $item->group;
                            }
                            if ($item->type_id == 11){
                                $item->cat = $item->brand . ' - ' . $item->texture;
                            }
                            if ($item->type_id == 10){
                                $item->cat = $item->type;
                            }
                            array_push($products, $item);
                            array_push($ids, $item->id);
                        }
                    }
                }
            }
        }

        $categories = DB::select('SELECT * FROM food_categories');

        $search = substr($search, 0, -1);


        if (mb_strlen($request->all()['search'])>30){
            $search=substr($search, 0, 30);
            $search=substr($search, 0, strrpos($search, ' '));
            $search=$search.'...';
        }


        if (empty($search)){
            $search='Все товары';
        }

        date_default_timezone_set ('Asia/Yekaterinburg');
        $stock = DB::select('SELECT percent, type_id FROM `stocks_by_types`  WHERE ? between start and end', [date('Y/m/d')]);

        
        $types = DB::select('SELECT * FROM types');

        foreach ($types as $type){
            $rep=0;
            foreach ($stock as $st){
                if ($st->type_id==$type->id){
                    $rep=$rep+1;
                }
            }
            if ($rep==0){

                $obj = (object)['percent'=>0, 'type_id'=>$type->id];
                array_push($stock, $obj);
            }
        }

        $i=0;
        for ($i = 0; $i < count($products); $i++) {
            $products[$i]->position=$i+1;
        }

        session(['title'=>'Результаты поиска']);

        return view("catalogFood", compact('products', 'categories', 'search', 'stock'));
    }







    public function favorite ($id) {

        $rep = DB::select('SELECT * FROM `favourites` WHERE user_id = ? AND product_id = ?', [Auth::user()->id, $id]);

        if ($rep == []){
            DB::insert('INSERT INTO `favourites` (`user_id`, `product_id`) VALUES (?, ?)', [Auth::user()->id, $id]);
        } else {
            DB::delete('DELETE FROM `favourites` WHERE user_id = ? AND product_id = ?', [Auth::user()->id, $id]);
        }

        return redirect ('/showProduct/' . $id . '#fav');
    }



    public function getFavorite (){
        // dd('123');
        $favProducts = DB::select('SELECT * FROM favourites WHERE user_id = ?', [Auth::user()->id]);
        $products = [];

        foreach ($favProducts as $item){
            $one = DB::select('SELECT * FROM products WHERE id = ?', [$item->product_id])[0];
            array_push($products, $one);
        }
        
        session(['title'=>'Избранное']);

        return view("favPage", compact('products'));
    }



    public function delfav($id) {
        DB::delete('DELETE FROM `favourites` WHERE user_id = ? AND product_id = ?', [Auth::user()->id, $id]);

        return redirect ('/getFavorite/');
    }



    public function getCosmetics(){
        session(['range'=> 100]);
        $areaF = [];
        $brandF = [];
        $formatF = [];
        $spfF = [];
        $textureF = [];
        $products = DB::select('SELECT *, 
        (SELECT areas.zone FROM areas WHERE areas.id = cosmetics.area_id) as area,
        (SELECT spfs.spf FROM spfs WHERE spfs.id = cosmetics.spf_id) as spf,
        (SELECT textures.texture FROM textures WHERE textures.id = cosmetics.texture_id) as texture,
        (SELECT brands.name FROM brands WHERE brands.id = cosmetics.brand_id) as brand,
        (SELECT formats.format FROM formats WHERE formats.id = cosmetics.format_id) as format
        FROM products INNER JOIN cosmetics ON cosmetics.product_id = products.id WHERE type_id = 11 AND count > 0');
        
        $areas = DB::select('SELECT * FROM areas');
        $brands = DB::select('SELECT * FROM brands');
        $formats = DB::select('SELECT * FROM formats');
        $spfs = DB::select('SELECT * FROM spfs');
        $textures = DB::select('SELECT * FROM textures');

        $max = DB::select('SELECT MAX(price) as max FROM `products` WHERE type_id = 11')[0]->max;
        $min = DB::select('SELECT MIN(price) as min FROM `products` WHERE type_id = 11')[0]->min;
        $maxF = $max;
        $minF = 0;
        date_default_timezone_set ('Asia/Yekaterinburg');

        $stock = DB::select('SELECT *, (SELECT types.direction FROM types WHERE types.id = stocks_by_types.type_id) as dire FROM `stocks_by_types`  WHERE ? between start and end AND type_id = ?', [date('Y/m/d'), 11]);
        if ($stock!=[]){
            $stock=$stock[0]->percent;
        } else {
            $stock=0;
        }

        $i=0;
        for ($i = 0; $i < count($products); $i++) {
            $products[$i]->position=$i+1;
        }
        
        session(['title'=>'Косметика']);

        return view("catalogCosmetic", compact('products', 'areas', 'brands', 'formats', 'spfs', 'textures', 'min', 'max', 'areaF', 'brandF', 'formatF', 'spfF', 'textureF', 'maxF', 'minF', 'stock'));
    }


    public function filtrCosmetic(Request $request){
        $query = 'SELECT * FROM cosmetics WHERE ';

        session()->forget('session');
        
        $areaF = [];
        $brandF = [];
        $formatF = [];
        $spfF = [];
        $textureF = [];

        
        foreach ($request->all() as $item){
            // var_dump();
            if (substr($item, 0, 4) == 'area'){
                $query = $query . 'area_id = ' . substr($item, 5) . ' OR ' ;
                array_push($areaF, substr($item, 5));
            }
            if (substr($item, 0, 5) == 'brand'){
                $query = $query . 'brand_id = ' . substr($item, 6) . ' OR ' ;
                array_push($brandF, substr($item, 6));

            }
            if (substr($item, 0, 6) == 'format'){
                $query = $query . 'format_id = ' . substr($item, 7) . ' OR ' ;
                array_push($formatF, substr($item, 7));

            }
            if (substr($item, 0, 3) == 'spf'){
                $query = $query . 'spf_id = ' . substr($item, 4) . ' OR ' ;
                array_push($spfF, substr($item, 4));

            }
            if (substr($item, 0, 7) == 'texture'){
                $query = $query . 'texture_id = ' . substr($item, 8) . ' OR ' ;
                array_push($textureF, substr($item, 8));

            }
        }
        $products = [];

        if ($query == 'SELECT * FROM cosmetics WHERE '){
            $query = 'SELECT * FROM cosmetics';
        } else {
            $query = substr($query, 0, -3);
        }
        
        $prod = DB::select($query);
        foreach ($prod as $item){
            $one = DB::select('SELECT *, 
            (SELECT areas.zone FROM areas WHERE areas.id = cosmetics.area_id) as area,
            (SELECT spfs.spf FROM spfs WHERE spfs.id = cosmetics.spf_id) as spf,
            (SELECT textures.texture FROM textures WHERE textures.id = cosmetics.texture_id) as texture,
            (SELECT brands.name FROM brands WHERE brands.id = cosmetics.brand_id) as brand,
            (SELECT formats.format FROM formats WHERE formats.id = cosmetics.format_id) as format
            FROM products INNER JOIN cosmetics ON cosmetics.product_id = products.id WHERE products.id = ? AND count > 0 AND price>=? AND price<=?', [$item->product_id, $request->all()['min'], $request->all()['max']]);
        
            if ($one!=[]){
                array_push($products, $one[0]);
            }
            
        }

        $areas = DB::select('SELECT * FROM areas');
        $brands = DB::select('SELECT * FROM brands');
        $formats = DB::select('SELECT * FROM formats');
        $spfs = DB::select('SELECT * FROM spfs');
        $textures = DB::select('SELECT * FROM textures');

        $max = DB::select('SELECT MAX(price) as max FROM `products` WHERE type_id = 11')[0]->max;
        $min = DB::select('SELECT MIN(price) as min FROM `products` WHERE type_id = 11')[0]->min;
        $maxF = $request->all()['max'];
        $minF = $request->all()['min'];
        date_default_timezone_set ('Asia/Yekaterinburg');

        $stock = DB::select('SELECT *, (SELECT types.direction FROM types WHERE types.id = stocks_by_types.type_id) as dire FROM `stocks_by_types`  WHERE ? between start and end AND type_id = ?', [date('Y/m/d'), 11]);
        if ($stock!=[]){
            $stock=$stock[0]->percent;
        } else {
            $stock=0;
        }


        // $range = session(['range'=> round($maxF/($max/100))]);
        // $range=round($maxF/($max/100));
        session(['range'=> round($maxF/($max/100))]);

        // dd($max, $maxF, $range);

        $i=0;
        for ($i = 0; $i < count($products); $i++) {
            $products[$i]->position=$i+1;
        }
        
        session(['title'=>'Косметика']);


        return view("catalogCosmetic", compact('products', 'areas', 'brands', 'formats', 'spfs', 'textures', 'min', 'max', 'areaF', 'brandF', 'formatF', 'spfF', 'textureF', 'maxF', 'minF', 'stock'));
    }


    public function getProductsByBrand($id){
        $areaF = [];
        $brandF = [$id];
        $formatF = [];
        $spfF = [];
        $textureF = [];
        $products = DB::select('SELECT *, 
        (SELECT areas.zone FROM areas WHERE areas.id = cosmetics.area_id) as area,
        (SELECT spfs.spf FROM spfs WHERE spfs.id = cosmetics.spf_id) as spf,
        (SELECT textures.texture FROM textures WHERE textures.id = cosmetics.texture_id) as texture,
        (SELECT brands.name FROM brands WHERE brands.id = cosmetics.brand_id) as brand,
        (SELECT formats.format FROM formats WHERE formats.id = cosmetics.format_id) as format
        FROM products INNER JOIN cosmetics ON cosmetics.product_id = products.id WHERE type_id = 11 AND count > 0 AND brand_id = ?', [$id]);
        
        $areas = DB::select('SELECT * FROM areas');
        $brands = DB::select('SELECT * FROM brands');
        $formats = DB::select('SELECT * FROM formats');
        $spfs = DB::select('SELECT * FROM spfs');
        $textures = DB::select('SELECT * FROM textures');

        $max = DB::select('SELECT MAX(price) as max FROM `products` WHERE type_id = 11')[0]->max;
        $min = DB::select('SELECT MIN(price) as min FROM `products` WHERE type_id = 11')[0]->min;
        $maxF =  DB::select('SELECT MAX(price) as max FROM `products` WHERE type_id = 11')[0]->max;
        $minF = 0;

        date_default_timezone_set ('Asia/Yekaterinburg');

        $stock = DB::select('SELECT *, (SELECT types.direction FROM types WHERE types.id = stocks_by_types.type_id) as dire FROM `stocks_by_types`  WHERE ? between start and end AND type_id = ?', [date('Y/m/d'), 11]);
        if ($stock!=[]){
            $stock=$stock[0]->percent;
        } else {
            $stock=0;
        }

        $i=0;
        for ($i = 0; $i < count($products); $i++) {
            $products[$i]->position=$i+1;
        }
        
        foreach ($brands as $item){
            if ($item->id==$id){
                session(['title'=>$item->name]);
            }
        }

        

        return view("catalogCosmetic", compact('products', 'areas', 'brands', 'formats', 'spfs', 'textures', 'min', 'max', 'areaF', 'brandF', 'formatF', 'spfF', 'textureF', 'maxF', 'minF', 'stock'));
    }



    public function getProductsByTexture($id){
        $areaF = [];
        $brandF = [];
        $formatF = [];
        $spfF = [];
        $textureF = [$id];
        $products = DB::select('SELECT *, 
        (SELECT areas.zone FROM areas WHERE areas.id = cosmetics.area_id) as area,
        (SELECT spfs.spf FROM spfs WHERE spfs.id = cosmetics.spf_id) as spf,
        (SELECT textures.texture FROM textures WHERE textures.id = cosmetics.texture_id) as texture,
        (SELECT brands.name FROM brands WHERE brands.id = cosmetics.brand_id) as brand,
        (SELECT formats.format FROM formats WHERE formats.id = cosmetics.format_id) as format
        FROM products INNER JOIN cosmetics ON cosmetics.product_id = products.id WHERE type_id = 11 AND count > 0 AND texture_id = ?', [$id]);
        
        $areas = DB::select('SELECT * FROM areas');
        $brands = DB::select('SELECT * FROM brands');
        $formats = DB::select('SELECT * FROM formats');
        $spfs = DB::select('SELECT * FROM spfs');
        $textures = DB::select('SELECT * FROM textures');

        $max = DB::select('SELECT MAX(price) as max FROM `products` WHERE type_id = 11')[0]->max;
        $min = DB::select('SELECT MIN(price) as min FROM `products` WHERE type_id = 11')[0]->min;
        $maxF =  DB::select('SELECT MAX(price) as max FROM `products` WHERE type_id = 11')[0]->max;
        $minF = 0;

        date_default_timezone_set ('Asia/Yekaterinburg');

        $stock = DB::select('SELECT *, (SELECT types.direction FROM types WHERE types.id = stocks_by_types.type_id) as dire FROM `stocks_by_types`  WHERE ? between start and end AND type_id = ?', [date('Y/m/d'), 11]);
        if ($stock!=[]){
            $stock=$stock[0]->percent;
        } else {
            $stock=0;
        }
        
        $i=0;
        for ($i = 0; $i < count($products); $i++) {
            $products[$i]->position=$i+1;
        }
        
        foreach ($textures as $item){
            if ($item->id==$id){
                session(['title'=>$item->texture]);
            }
        }


        return view("catalogCosmetic", compact('products', 'areas', 'brands', 'formats', 'spfs', 'textures', 'min', 'max', 'areaF', 'brandF', 'formatF', 'spfF', 'textureF', 'maxF', 'minF', 'stock'));
    }









    public function getNews (){

        $news = DB::select('SELECT * FROM news ORDER BY created_at DESC');
        
        session(['title'=>'Новости']);

        return view("news", compact('news'));
    }

    function asd (){
        return (1);
    }


    public function getNew($id){
        $month = [
            'Января',
            'Февраля',
            'Марта',
            'Апреля',
            'Мая',
            'Июня',
            'Июля',
            'Августа',
            'Сентября',
            'Октября',
            'Ноября',
            'Декабря'
          ];
          
        
        $new = DB::select('SELECT * FROM news WHERE id = ?', [$id])[0];

        $day = substr($new->created_at, -2);

        if (substr($day, 0, 1) == 0){
            $day = substr($day, -1);
        }
        
        session(['title'=>$new->title]);


        $new->created_at = $day . ' ' . $month[substr($new->created_at, 5,2)-1] . ' ' . substr($new->created_at, 0,4);

        return view("new", compact('new'));
    }



    public function getBrands(){
        $what = 'Бренды';
        $groups = DB::select('SELECT * FROM `brands` ORDER BY name ');
        
        session(['title'=>'Бренды']);

        return view("groups", compact('groups', 'what'));
    }



    public function getFoodByCategory($id){
        $products = DB::select('SELECT *, (SELECT foods.food_category_id FROM foods WHERE foods.product_id = products.id) as cat_id, (SELECT food_categories.name FROM food_categories WHERE food_categories.id = cat_id) as cat FROM products having type_id = 10 AND cat_id = ?', [$id]);
        $categories = DB::select('SELECT * FROM food_categories');
        $search = false;
        date_default_timezone_set ('Asia/Yekaterinburg');

        $stock = DB::select('SELECT percent, type_id FROM `stocks_by_types`  WHERE ? between start and end', [date('Y/m/d')]);

        
        $types = DB::select('SELECT * FROM types');

        foreach ($types as $type){
            $rep=0;
            foreach ($stock as $st){
                if ($st->type_id==$type->id){
                    $rep=$rep+1;
                }
            }
            if ($rep==0){

                $obj = (object)['percent'=>0, 'type_id'=>$type->id];
                array_push($stock, $obj);
            }
        }

        $i=0;
        for ($i = 0; $i < count($products); $i++) {
            $products[$i]->position=$i+1;
        }
        
        foreach ($categories as $item){
            if ($item->id==$id){
                session(['title'=>$item->name]);
            }
        }

        return view("catalogFood", compact('products', 'categories', 'search', 'stock'));
    }



    public function catalogFood(){
        date_default_timezone_set ('Asia/Yekaterinburg');
        $products = DB::select('SELECT *, (SELECT foods.food_category_id FROM foods WHERE foods.product_id = products.id) as cat_id, (SELECT food_categories.name FROM food_categories WHERE food_categories.id = cat_id) as cat FROM products WHERE type_id = 10');
        
        $categories = DB::select('SELECT * FROM food_categories');
        
        $search = false;

        $stock = DB::select('SELECT percent, type_id FROM `stocks_by_types`  WHERE ? between start and end', [date('Y/m/d')]);

        
        $types = DB::select('SELECT * FROM types');

        foreach ($types as $type){
            $rep=0;
            foreach ($stock as $st){
                if ($st->type_id==$type->id){
                    $rep=$rep+1;
                }
            }
            if ($rep==0){

                $obj = (object)['percent'=>0, 'type_id'=>$type->id];
                array_push($stock, $obj);
            }
        }


        $i=0;
        for ($i = 0; $i < count($products); $i++) {
            $products[$i]->position=$i+1;
        }

        $i=0;
        for ($i = 0; $i < count($products); $i++) {
            $products[$i]->position=$i+1;
        }
        
        session(['title'=>'Питание']);

        return view("catalogFood", compact('products', 'categories', 'search', 'stock'));
    }




    function countBasket (){
        $sum = DB::select("SELECT SUM(count) as sum FROM baskets WHERE user_id=?", [Auth::user()->id]);
        return $sum[0];
    }



}