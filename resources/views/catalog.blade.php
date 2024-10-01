@include('/layouts/header')

<input type="hidden" name="user_id" id="user_id" value="{{Auth::user() !== null? Auth::user()->id:null}}">
<input type="hidden" name="is_blocked" id='is_blocked' value="{{Auth::user() !== null? Auth::user()->is_blocked:null}}">




@foreach ($types as $type)
    <?php $stoks = DB::select('SELECT * FROM `stocks_by_types` WHERE ? between start and end AND type_id = ?', [date('Y-m-d'), $type->id]); if ($stoks!=[]) {$stoks=$stoks[0]->percent;} else {$stoks='none';} ?>
    <input type="hidden" class='stock_{{$type->id}}' value='{{$stoks}}' id="">
@endforeach









@if ($group != false)
        <div class="container-fluid pt-5">
            <div class="container">
    
                <div class="row gx-5">
                    <div class="col-lg-6 mb-5 mb-lg-0" style="min-height: 400px;">
                        <div class="position-relative h-100">
                            <img class="position-absolute w-100 h-100" src="/groups/{{$group->photo_group}}" alt="{{$group->photo_group}}" style="object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-lg-6 pb-5">
                        <h2 class="mb-4">{{$group->group_name}}</h2>
                        <p class="mb-5">{{$group->description}}</p>
                        <div class="col-sm-6">

                            <h4 class="text-uppercase">Товаров на сайте:</h4>
                            <br>
                            <div class="d-flex align-items-center justify-content-center bg-primary border-inner mb-4" style="height: 90px;">
                                <h1 style="color: white; margin-top:5px;">{{$countProdGroup}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endif










<!-- Начало фильтрации -->
@csrf
    <div class="container-fluid about py-5">
        <div class="container">
            <div class="position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">

                <div class='bblock section-title '>
                    <h2>Каталог</h2>
                </div>

            </div>



            <div class="tab-class text-center">
                
                <ul class="nav nav-pills d-inline-flex justify-content-center bg-dark text-uppercase border-inner p-4 mb-5">
                    
                    <li class="nav-item" style='margin-right: 10px;'>
                        <select name="type" id="type" class="nav-link text-white">
                            <option class="bg-dark" value="">Все типы</option>
                            @foreach($types as $type)
                            <option class="bg-dark" {{isset($id) && $id == $type->id? 'selected':''}} value="{{$type->id}}">{{$type->type_products}}</option>
                            @endforeach
                        </select>
                    </li>

                    @if ($group == false)
                    <li class="nav-item">
                        <select name="group" id="group" class="nav-link text-white">
                            <option class="bg-dark" value="">Все группы</option>
                            @foreach($groups as $group)
                            <option class="bg-dark" value="{{$group->id}}">{{$group->group_name}}</option>
                            @endforeach
                        </select>
                    </li>
                    @else

                    <li class="nav-item">
                        <select name="group" id="group" class="nav-link text-white" style='display: none;'>
                            <option class="bg-dark" value="{{$group->id}}">{{$group->group_name}}</option>

                        </select>
                    </li>
                    @endif


                    
                    <li class="nav-item">
                        <button id="priceOrder" class="nav-link text-white">Цена</button>
                    </li>

                    <li class="nav-item">

                        <button id="hoviznOrder" class="nav-link text-white">Новизна</button>
                    </li>

                </ul>

            </div>
        </div>
    </div>
















    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5 cards-block">
                @foreach ($products as $product)
                @if ($product->type_id == '3')
                    <?php
                        $albid=DB::select('SELECT id FROM `albums` WHERE product_id = ?', [$product->id]);
                        $sum = DB::select('SELECT SUM(count) as sum FROM `versions` WHERE album_id =?', [$albid[0]->id])[0]->sum;
                        $product->count = $sum;
                    ?>
                @endif
                @if ($product->count !=0)
                <div class="col-lg-4 col-md-6 @if($product->position>9) ordersHidden lastest @else ordersBlock @endif">
                    <div class="team-item">
                        <a href="{{action('App\Http\Controllers\MainController@showProduct', ['id' => $product->id])}}">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="/products/{{$product->photo}}" alt="" style="height: 350px; object-fit: cover;">
                            </div>
                        </a>
                        <?php 
                        date_default_timezone_set ('Asia/Yekaterinburg');
                        $stok = DB::select('SELECT * FROM `stocks_by_types` WHERE ? between start and end AND type_id = ?', [date('Y/m/d'), $product->type_id]); if($stok!=[]) {$stok=$stok[0]->percent;} else{$stok=0;} ?>
                        

                        <h4 class="text-uppercase" style="margin-top: 15px;">{{$product->name}} <span style="color:gray;">{{round(($product->price/100) * (100-$stok))}} ₽</span>@if ($stok!=0)<span style='color: red; font-size: 60%;'>-{{$stok}}%</span>@endif</h4>



                        @if ($product->productGroup != null)
                        <h6>{{$product->productGroup->name}}</h6>
                        @endif
                        <div class='row'>
                            @if($product->productAlbums)
                                <div class="versionSelecter">
                                    <select name="" id="vers{{$product->id}}" style='border: 0,5px black solid; padding: 3px; border-radius: 5px;'>
                                        @foreach ($product->productAlbums->albumVersion as $item)
                                            <?php $countver = DB::select('SELECT count FROM `versions` WHERE id = ?', [$item->id]); ?>
                                                @if ($countver[0]->count>0)
                                                    <option value="{{$item->id}}" >{{$item->version_name}}</option>
                                                @endif
                                        @endforeach
                                    </select>
                                </div>
                            @else
                            <div style='padding-top: 32px;'></div>
                            @endif
                            @if(Auth::user() && auth()->user()->is_blocked !=1)
                            <button class="addBasket bg-dark" data-id="{{$product->id}}" style="color:white; border-radius: 5px;  border: 0px; padding: 10px; padding-left: 20px; padding-right: 20px; margin-top: 20px;">В корзину</button>
                            @elseif (Auth::user()  && auth()->user()->is_blocked ==1)
                            <h6 style='color:red;'>Вы заблокированы</h6>
                            @else
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <p style='text-align: center;'>
        <button class='more bg-dark' style="color:white; border-radius: 5px; border: 0px; padding: 10px; padding-left: 20px; padding-right: 20px; margin-top: 20px; max-width: 300px;">Показать еще</button>
    </p>

    
    <script>
        const more = document.querySelector('.more');
        let blocks = document.querySelectorAll('.lastest');
        if (blocks.length==0){
            more.classList.add('ordersHidden')
        }

        more.addEventListener('click', function(){
        let shows = document.querySelectorAll('.ordersBlock');
        let blocks = document.querySelectorAll('.lastest');
        

            let i = 0;
            while (i < 9){
                blocks[i].classList.remove('lastest');
                blocks[i].classList.add('ordersBlock');
                i=i+1;
                let many = document.querySelectorAll('.lastest');

                if (many.length<=0){
                    more.classList.add('ordersHidden')
                }
            }


        })
    </script>


<script src="/assets/js/catalog.js" defer type="module"></script>
@include('/layouts/footer')
