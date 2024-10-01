@include('/layouts/header')

@if ($search == false)


    <div class="tab-class text-center" style='margin-top: 30px;'>
        <ul class="nav nav-pills d-inline-flex justify-content-center bg-dark text-uppercase border-inner p-4 mb-5">
                <li class="nav-item hover">
                    <a class="nav-link text-white" href="/catalogFood">Все товары</a>
                </li>
            @foreach ($categories as $category)
                <li class="nav-item hover">
                    <a class="nav-link text-white" href="{{action('App\Http\Controllers\MainController@getFoodByCategory', ['id' => $category->id] )}}">{{$category->name}}</a>
                </li>
            @endforeach
        </ul>
    </div>

@else
    <div class="position-relative text-center mx-auto mb-5 pb-3" style="margin-top: 50px;">
        <div class='bblock section-title '>
            @if ($search!='Все товары')
                <h2>Результаты по запросу "{{$search}}"</h2>
            @else
                <h2>{{$search}}</h2>
            @endif
        </div>
    </div>
@endif

<div class="container-fluid py-5">
    <div class="container">
        <div class="row g-5 cards-block">
            @foreach ($products as $product)
            <div class="col-lg-4 col-md-6 @if($product->position>6) ordersHidden lastest @else ordersBlock @endif">
                <div class="team-item">
                    <a href="{{action('App\Http\Controllers\MainController@showProduct', ['id' => $product->id])}}">
                        <div class="position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="/products/{{$product->photo}}" alt="" style="height: 350px; object-fit: cover;">
                        </div>
                    </a>
                    <h4 class="text-uppercase" style="margin-top: 15px;">{{$product->name}} 
                    
                    @foreach ($stock as $st)
                        @if ($st->type_id==$product->type_id)
                            <span style="color:gray;">{{round(($product->price/100) * (100-$st->percent))}} ₽</span>@if ($st->percent!=0)<span style='color: red; font-size: 60%;'>-{{$st->percent}}%</span>@endif
                        @endif
                    @endforeach
                
                </h4>
                    <h6>{{$product->cat}}</h6>
                    @if ($search == false)
                        <div class='row'>
                            @if(Auth::user() && auth()->user()->is_blocked !=1)
                            <button class="addBasket bg-dark" data-id="{{$product->id}}" style="color:white; border-radius: 5px; border: 0px; padding: 10px; padding-left: 20px; padding-right: 20px; margin-top: 20px;">В корзину</button>
                            @elseif (Auth::user()  && auth()->user()->is_blocked ==1)
                            <h6 style='color:red;'>Вы заблокированы</h6>
                            @else
                            @endif
                        </div>
                    @endif
                </div>
            </div>
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
            while (i < 6){
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
