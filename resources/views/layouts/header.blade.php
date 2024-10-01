<?php
use Illuminate\Support\Facades\DB;
$infos = DB::select('SELECT * FROM information');
$kpops = DB::select('SELECT * FROM types WHERE direction = "kpop"');
$brands = DB::select('SELECT * FROM brands ORDER BY name');
$categories = DB::select('SELECT * FROM textures ORDER BY texture');
$foods = DB::select('SELECT * FROM food_categories');

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title><?=session()->get('title') ?? 'STOREKOR'?> | STOREKOR</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Storekor kpop stray kids bts альбом карты корейская продукция косметика лапша еда питание корея " name="keywords">
    <meta content="Интернет-магазин Storekor предлагает широкий ассортимент корейской продукции: косметика, продукты питания, k-pop товары" name="description">
    <meta name="author" content="Storekor">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Интернет-магазин корейской продукции Storekor">
    <meta property="og:description" content="Широкий выбор корейской косметики, продуктов питания и k-pop товаров от магазина Storekor.">
    <meta property="og:image" content="/info/logo.png">
    
    <meta name="yandex-verification" content="3635a1edcd598bf0" />
    <meta name="google-site-verification" content="RKR7blDE3n-u1EH5OBAe-HHo9qPMRfksG-9-e3yCvjQ" />

    <link href="/info/logo.png" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;600;700&family=Pacifico&display=swap" rel="stylesheet"> 

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">


    <link href="/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">

    
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body>


<input type="hidden" name="user_id" id="user_id" value="{{Auth::user() !== null? Auth::user()->id:null}}">


    <nav class="navbar navbarscroll navbar-expand-lg bg-dark navbar-dark shadow-sm py-3 py-lg-0 px-3 px-lg-0" >
        <a href="/" class="navbar-brand d-block d-lg-none">
            <h1 class="m-0 text-uppercase text-white"><img src="/info/logo.png" style="height: 50px; margin-left: 40px; padding-right: 20px;" alt="">STOREKOR</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto mx-lg-auto py-0">
                <a href="/" class="nav-item nav-link active">Главная</a>



                



                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">K-pop</a>
                    <div class="dropdown-menu m-0">
                        <a href="/catalog" class="dropdown-item">Все товары</a>
                        <a href="/getgGroups" class="dropdown-item">Группы</a>
                        @foreach ($kpops as $type)
                        <a href="{{action('App\Http\Controllers\MainController@getProductsByType', ['id' => $type->id] )}}" class="dropdown-item">{{$type->type_products}}</a>
                        @endforeach
                    </div>
                </div>


                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Косметика</a>
                    <div class="dropdown-menu m-0 contcos" style='min-width: 400px; max-width: 500px;'>
                        
                        <a href="/catalogCosmetc" class="dropdown-item">Вся косметика</a>
                        <p class='dropdown-item'><b><a href="/getBrands">Бренды</a></b></p>
                        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; text-align: left;" class='blockcos'>
                        @foreach ($brands as $brand)
                            <a href="{{action('App\Http\Controllers\MainController@getProductsByBrand', ['id' => $brand->id] )}}" style='flex: 0 0 33%; border-left: 1px gray solid;' class="dropdown-item itemcos">{{$brand->name}}</a>
                        @endforeach
                        </div>

                        <br>
                        <hr>


                        <p class='dropdown-item'><b>Категория</b></p>
                        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; text-align: left;" class='blockcos'>
                        @foreach ($categories as $category)
                            <a href="{{action('App\Http\Controllers\MainController@getProductsByTexture', ['id' => $category->id] )}}" style='flex: 0 0 33%; border-left: 1px gray solid;' class="dropdown-item itemcos">{{$category->texture}}</a>
                        @endforeach
                        </div>
    
                    </div>
                </div>








                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Питание</a>
                    <div class="dropdown-menu m-0">
                        <a href="/catalogFood" class="dropdown-item">Все товары</a>
                        @foreach ($foods as $food)
                        <a href="{{action('App\Http\Controllers\MainController@getFoodByCategory', ['id' => $food->id] )}}" class="dropdown-item">{{$food->name}}</a>
                        @endforeach
                    </div>
                </div>






                <a href="/getNews" class="nav-item nav-link">Новости</a>


                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Информация</a>
                    <div class="dropdown-menu m-0">
                        @foreach ($infos as $info)
                        <a href="/getInfo/{{$info->id}}" class="dropdown-item">{{$info->heading}}</a>
                        @endforeach
                    </div>
                </div>


                @if(Auth::user() && auth()->user()->is_blocked!=1)
                <a href="/basket" class="nav-item nav-link">Корзина</a>
                @endif





                @if (Route::has('login'))
                @auth
                
                @else
                <a href="{{ route('login') }}" class="nav-item nav-link">Войти</a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="nav-item nav-link">Регистрация</a>
                @endif

                @endauth
                @endif
                <div class='searchmainblock'>
                @auth
                <a style='display: inline-block;' href="{{ url('/home') }}" class="nav-item nav-link">
                {{ Auth::user()->name }} 
                <!-- <img src="/users/{{ Auth::user()->avatar }}" alt="" style='width: 30px;'> -->
                
                </a>
                @endauth

                <div class="nav-item dropdown nav-link searchheaderblock" >
                    <form action="/search" method='post'>
                        @csrf
                        <div class="input-group">
                            <div class="form-outline input-wrapper" data-mdb-input-init>
                                <input autocomplete="off" name='search' placeholder='Поиск' id="sssearch" type="search" id="form1" class="form-control search" />
                                <button class="search"> 
                                    <i class="fas fa-search lup"></i>
                                </button> 
                            </div>
                            <!-- <button class="btn btn-primary search" >
                                <i class="fas fa-search lup"></i>
                            </button> -->
                        </div>
                    </form>
                    
                    <div class="m-0 dropdown-menu" id="searchResult" style='padding-bottom: 0px;'></div>
                </div>

                </div>
            </div>
        </div>
    </nav>

    <div id="popup" class="popup">
      <span class="close" id="closePopup">&times;</span>
      <p>Товар добавлен в Корзину</p>
    </div>
    <script>
        const blockcos = document.querySelectorAll('.blockcos');
        const contcos = document.querySelector('.contcos');

        var screenWidth = window.screen.width
        window.addEventListener('resize', function() {
            var screenWidth = window.screen.width
            if (screenWidth <=430){
            contcos.style.minWidth = null;
            contcos.style.maxWidth = null;
            
            blockcos.forEach(item => {
                item.style.display = null;
                item.style.flexWrap = null;
                item.style.justifyContent = null;
                item.style.textAlign = null;
            })
            } else {
                contcos.style.minWidth = '400px';
                contcos.style.maxWidth = '500px';

                blockcos.forEach(item => {
                item.style.display = 'flex';
                item.style.flexWrap = 'wrap';
                item.style.justifyContent = 'space-between';
                item.style.textAlign = 'left';
            })

            }
        })


        if (screenWidth <=430){
            contcos.style.minWidth = null;
            contcos.style.maxWidth = null;
            
            blockcos.forEach(item => {
                item.style.display = null;
                item.style.flexWrap = null;
                item.style.justifyContent = null;
                item.style.textAlign = null;
            })
        }

    </script>
    <style>
        @media screen and (min-width: 1100px) {
            .searchheaderblock{
                display: inline-block;
            }
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <script src="/assets/js/search.js" defer type="module"></script>