@include('/layouts/header')


<div class="container-fluid bg-dark bg-img p-5 mb-5">
    <div class="row">
        <div class="col-12 text-center">
            <h1 class="display-4 text-uppercase text-white">Ваши заказы</h1>
        </div>
    </div>
</div>

<div class="" style="margin-top: 90px;">
        <div class="container" >
            <div class="row g-5 mb-5" >
                @foreach ($orders as $order)
                <div class="col-lg-4 col-md-6 @if($order->position>3) ordersHidden lastest @else ordersBlock @endif" >
                    <div class="bg-primary border-inner text-center text-white p-5" >
                       
                        <h4 class="text-uppercase my-2">Заказ от {{$order->created_at}}</h4>
                        <h6 class="text-uppercase my-2">Статус: {{$order->stage}}</h6>
                        <i><img src="/icons/locwh.png" alt="" style='max-width: 55px;'></i>
                        @if ($order->track_code != NULL)
                        <a href="https://www.pochta.ru/tracking#{{$order->track_code}}"><h4 class="text-uppercase my-2">Трек-код: {{$order->track_code}}</h4></a>
                        @else
                        <h4 class="text-uppercase my-2">Трек-код скоро появится</h4>
                        @endif
                        <h6 class="text-uppercase my-2">Общая тоимость: {{$order->price}} ₽</h6>
                        <br>
                        <a href="{{action('App\Http\Controllers\MainController@getOrdersProduct', ['id' => $order->id])}}"><h3 class="text-uppercase my-2" style="color: white;">Товары в заказе</h3></a>

                    </div>
                    
                </div>
                @endforeach
                
                @if (count($orders)>3)
                    <p style='text-align: center'>
                        <button class='showOrders bg-dark' style="color:white; border-radius: 5px; border: 0px; padding: 10px; padding-left: 20px; padding-right: 20px; margin-top: 20px; max-width: 300px;">Показать все заказы</button>
                    </p>
                @endif

            </div>

        </div>
    </div>


    <script>
        const showOrders = document.querySelector('.showOrders');
        const blocks = document.querySelectorAll('.lastest');

        showOrders.addEventListener('click', function(){

            let i = 0;
            while (i < blocks.length){
                blocks[i].classList.toggle('ordersBlock');
                i=i+1;
            }

            let btn = document.querySelectorAll('.ordersBlock');
            if (btn.length>3){
                showOrders.innerHTML = "Скрыть заказы";
            } else {
                showOrders.innerHTML = "Показать все заказы";
            }
            
        })
    </script>


@include('/layouts/links')
