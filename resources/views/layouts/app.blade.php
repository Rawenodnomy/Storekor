@include('/layouts/header')




@guest

@else
<div class="container-fluid bg-dark bg-img p-5 mb-5 mt-5">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 text-uppercase text-white">{{ Auth::user()->name }}</h1>

                <div class="flex-shrink-0">
                    <img class="img-fluid" src="/users/{{ Auth::user()->avatar }}" alt="{{ Auth::user()->avatar }}" style="width: 250px; height: 250px; object-fit: cover;">

                </div>

                <div class="container-fluid about py-5">
                    <div class="container">
                        <div class="tab-class text-center">
                            <ul class="nav nav-pills d-inline-flex justify-content-center bg-dark text-uppercase border-inner p-4 mb-5">
                                <li class="nav-item elem">
                                    <a class="nav-link text-white" href="{{action('App\Http\Controllers\MainController@getOrdersByUser')}}">Мои заказы</a>
                                </li>
                                <li class="nav-item elem">
                                    <a class="nav-link text-white"  href="{{action('App\Http\Controllers\MainController@editProfile')}}">Редактировать профиль</a>
                                </li>
                                <li class="nav-item elem">
                                    <a class="nav-link text-white"  href="{{action('App\Http\Controllers\MainController@getContact')}}">Данные для доставки</a>
                                </li>
                                <li class="nav-item elem">
                                    <a class="nav-link text-white"  href="/getFavorite">Избранное</a>
                                </li>

                            </ul>
                            <br>
                            <ul class="nav nav-pills d-inline-flex justify-content-center bg-dark text-uppercase border-inner p-4 " style="margin-top: -20px">
                                
                                <li class="nav-item elem">
                                    <a class="nav-link text-white"  href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">Выход</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
            
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



@endguest





@if (session()->get('recently') != null && Auth::user())
    <div class="container-fluid py-5">
        <div class="container">
            <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
                <h1 class="display-4 text-uppercase" style="font-size: 200%;">Вы недавно смотрели</h1>
            </div>
            <div class="owl-carousel testimonial-carousel">

                @foreach ($recently as $res)
                    <div class="testimonial-item">
                        <a href="{{action('App\Http\Controllers\MainController@showProduct', ['id' => $res->id])}}">
                            <div class="d-flex align-items-center mb-3">
                                <img class="img-fluid flex-shrink-0" src="/products/{{$res->photo}}" style='max-height: 300px; object-fit: cover;'>
                            </div>
                            <h4>{{$res->name}} <span style="color: gray;">{{$res->price}} ₽</span></h4>
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endif







<main>
    @yield('content')
</main>
@include('/layouts/links')