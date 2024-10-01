@include('/layouts/header')

    <div class="container-fluid bg-dark bg-img p-5 mb-5">
        <div class="row">
            <div class="col-12 text-center">
                @if ($products !=[])
                    <h2 class="display-4 text-uppercase text-white">Избранные товары</h2>
                @else
                    <h2 class="display-4 text-uppercase text-white">Избранных товаров нет</h2>
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
                            
                            @foreach($products as $prod)
                            <div>
                                <div class="d-flex h-100">
                                    <div class="flex-shrink-3">
                                        <a href="/showProduct/{{$prod->id}}">
                                            <img class="img-fluid" src="/products/{{$prod->photo}}" alt="{{$prod->photo}}" style="width: 150px; height: 150px; object-fit: cover;">
                                        </a>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center text-start bg-secondary border-inner px-4" style="width: 100%;">
                                        
                                        <h5 class="text-uppercase">{{$prod->name}}</h5>
                                        <h6> <span style="color: gray;">{{$prod->price}} ₽</span></h6>

                                        <a href="{{action('App\Http\Controllers\MainController@delfav', ['id' => $prod->id])}}"  style='font-size:30%; position:absolute; right:5%; margin-bottom: 10px;'>
                                            <img src="/icons/heart.png" alt="" style='max-width: 30px;'>
                                        </a>



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



    @if ($products ==[])
    <br><br><br><br>
    <br><br><br><br>
    @endif



    @include('/layouts/footer')