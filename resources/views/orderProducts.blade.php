@include('/layouts/header')




<div class="container-fluid bg-dark bg-img p-5 mb-5">
    <div class="row">
        <div class="col-12 text-center">
            <h1 class="display-4 text-uppercase text-white">Товары в заказе от {{$order->created_at}} </h1>
            <h3 class="text-white">Всего {{$order->count}} товаров на общую стоимость {{$order->price}} ₽</h3>
            
            
            @if ($order->comment !=null)
            <h6 class="text-white">Комментарий к заказу: {{$order->comment}}</h6>
            @endif
            <hr style='height: 5px;'>
            @if ($order->track_code!=null)
                <a href="https://www.pochta.ru/tracking#{{$order->track_code}}"><h3 class=" text-white"><i><img src="/icons/locwh.png" alt="" style='max-width: 40px;'></i> Трек-код: {{$order->track_code}}</h3></a>
            @else
                <h3 class="text-white"><i><img src="/icons/locwh.png" alt="" style='max-width: 40px;'></i>Трек-код скоро появится</h3>
            @endif
        </div>
    </div>
</div>

<div class="container-fluid about py-5">
        <div class="container">

            <div class="tab-class text-center">
 
                <div class="">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-3">
                       
                            
                            @foreach($order_product as $product)
                            <div>
                                <div class="d-flex h-100">
                                    <div class="flex-shrink-3">
                                        <img class="img-fluid" src="/products/{{$product->photo}}" alt="{{$product->photo}}" style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center text-start bg-secondary border-inner px-4" style="width: 100%;">
                                        
                                        <h5 class="text-uppercase">{{$product->name}} @if($product->version_name) ({{$product->version_name}}) @endif</h5>
                                        <p>{{$product->type}}@if($product->group_name), {{$product->group_name}} @endif</p>
                                        <h6> <span style="color: gray;">{{$product->price}} ₽, Количество: {{$product->count_order}}</span></h6>
                                        
                                        </div>



                                    </div>
                                    
                                </div>
                            </div>
                            <br>
                            @endforeach
                            
                        </div>
                        

                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('/layouts/links')
