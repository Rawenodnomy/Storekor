@include('/layouts/header')



<p class="centertxt mt-50">
    <a class="btn btn-primary flitr-btn" style='border-radius: 5px;' data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
        Фильтрация
    </a>
</p>


<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasExampleLabel">Фильтрация</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
    </div>
    <div class="offcanvas-body">
        
        <div>
            <form action="/filtrCosmetic" method="post">
                @csrf
                <p><b>Область ухода</b></p>
                @foreach ($areas as $area)
                <div class="form-check form-switch">
                    
                <label class="form-check-label" for="{{$area->zone}}">{{$area->zone}}</label>
                    @if ($areaF !=[])
                            @if (in_array($area->id, $areaF))
                                <input class="form-check-input" type="checkbox" type="checkbox" value='area_{{$area->id}}' id='{{$area->zone}}' name='area_{{$area->zone}}' checked><br>
                            @else
                                <input class="form-check-input" type="checkbox" type="checkbox" value='area_{{$area->id}}' id='{{$area->zone}}' name='area_{{$area->zone}}'><br>
                            @endif
                    @else
                        <input class="form-check-input" type="checkbox" type="checkbox" value='area_{{$area->id}}' id='{{$area->zone}}' name='area_{{$area->zone}}'><br>
                    @endif
                    
                </div>
                @endforeach
                <hr>
                @foreach ($brands as $brand)
                <div class="form-check form-switch">
                <label class="form-check-label" for="{{$brand->name}}">{{$brand->name}}</label>
                    @if ($brandF !=[])
                        @if (in_array($brand->id, $brandF))
                            <input class="form-check-input" type="checkbox" value='brand_{{$brand->id}}' id='{{$brand->name}}' name='brand_{{$brand->name}}' checked><br>
                        @else
                            <input class="form-check-input" type="checkbox" value='brand_{{$brand->id}}' id='{{$brand->name}}' name='brand_{{$brand->name}}'><br>
                        @endif
                    @else
                        <input class="form-check-input" type="checkbox" value='brand_{{$brand->id}}' id='{{$brand->name}}' name='brand_{{$brand->name}}'><br>
                    @endif
                </div> 
                @endforeach
                <hr>
                @foreach ($formats as $format)
                <div class="form-check form-switch">

                    <label class="form-check-label" for="{{$format->format}}">{{$format->format}}</label>
                    @if ($formatF !=[])
                        @if (in_array($format->id, $formatF))
                            <input class="form-check-input" type="checkbox" value='format_{{$format->id}}' id='{{$format->format}}' name='format_{{$format->format}}' checked>  <br>
                        @else
                            <input class="form-check-input" type="checkbox" value='format_{{$format->id}}' id='{{$format->format}}' name='format_{{$format->format}}'>  <br>
                        @endif
                    @else
                        <input class="form-check-input" type="checkbox" value='format_{{$format->id}}' id='{{$format->format}}' name='format_{{$format->format}}'>  <br>
                    @endif
                    </div>
                @endforeach
                <hr>
                @foreach ($spfs as $spf)
                <div class="form-check form-switch">
                    <label class="form-check-label" for="{{$spf->spf}}">{{$spf->spf}}</label>
                    @if ($spfF !=[])
                        @if (in_array($spf->id, $spfF))
                            <input class="form-check-input" type="checkbox" value='spf_{{$spf->id}}' id='{{$spf->spf}}' name='spf_{{$spf->spf}}' checked> <br>
                        @else
                            <input class="form-check-input" type="checkbox" value='spf_{{$spf->id}}' id='{{$spf->spf}}' name='spf_{{$spf->spf}}'> <br>
                        @endif
                    @else
                        <input class="form-check-input" type="checkbox" value='spf_{{$spf->id}}' id='{{$spf->spf}}' name='spf_{{$spf->spf}}'> <br>
                    @endif
                    </div>
                @endforeach
                <hr>
                @foreach ($textures as $texture)
                <div class="form-check form-switch">
                    <label class="form-check-label" for="{{$texture->texture}}">{{$texture->texture}}</label>
                    @if ($textureF !=[])
                        @if (in_array($texture->id, $textureF))
                            <input class="form-check-input" type="checkbox" value='texture_{{$texture->id}}' id='{{$texture->texture}}' name='texture_{{$texture->texture}}' checked><br>
                        @else
                            <input class="form-check-input" type="checkbox" value='texture_{{$texture->id}}' id='{{$texture->texture}}' name='texture_{{$texture->texture}}'><br>
                        @endif
                    @else
                        <input class="form-check-input" type="checkbox" value='texture_{{$texture->id}}' id='{{$texture->texture}}' name='texture_{{$texture->texture}}'><br>
                    @endif
                    </div>
                @endforeach
    
                <hr>
    
                <div class="custom-wrapper"> 
                <div class="price-input-container"> 
                    <div class="price-input"> 
                        <div class="price-field"> 
                            <span class='span-price'>Минимальная цена</span> 
                            @if ($minF == null)
                                <input type="number" class="min-input range" value="0" name='min'> 
                            @else
                                <input type="number" class="min-input range" value="{{$minF}}" name='min'> 
                            @endif
                        </div> 
                        <div class="price-field"> 
                            <span class='span-price'>Максимальная цена</span> 
                            @if ($maxF == null)
                                <input type="number" name='max' class="max-input range" value="{{$max}}" max="{{$max}}"> 
                            @else
                                <input type="number" name='max' class="max-input range" value="{{$maxF}}" max="{{$max}}"> 
                            @endif
                        </div> 
                    </div> 
                    <div class="slider-container"> 
                        <div class="price-slider" style='left:{{round($minF/$max*100,4)}}%;right:{{round(100 - ($maxF /$max *100), 4)}}%;'> 
                        </div> 
                    </div> 
                </div> 
    
            <!-- Slider -->
            <div class="range-input"> 
                <input type="range"
                    class="min-range"
                    min="0"
                    max="{{$max}}"
                    
                    value="{{$minF}}"
                    step="1"> 
                <input type="range"
                    class="max-range"
                    min="0"
                    max="{{$max}}"
                    value="{{$maxF}}"
                    step="1"> 
            </div> 
            </div> 
    
                <style>
    
    .price-input-container { 
        width: 100%; 
    } 
    
    .price-input .price-field { 
        display: flex; 
        margin-bottom: 22px; 
    } 
    
    
    .price-input { 
        width: 100%; 
        font-size: 19px; 
        color: #555; 
    } 
    
    input::-webkit-outer-spin-button, 
    input::-webkit-inner-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    } 
    
    .slider-container { 
        /* полоска */
        width: 100%; 
        /* <?=session()->get('range')?> */
    } 
    
    .slider-container { 
        height: 6px; 
        position: relative; 
        background: #e4e4e4; 
        border-radius: 5px; 
    } 
    
    .slider-container .price-slider { 
        height: 100%; 
        left: 0%; 
        right: 0; 
        position: absolute; 
        border-radius: 5px; 
        background: #E88F2A; 
    } 
    
    .range-input { 
        position: relative; 
    } 
    
    .range-input input { 
        position: absolute; 
        width: 100%; 
        height: 5px; 
        background: none; 
        top: -5px; 
        pointer-events: none; 
        cursor: pointer; 
        -webkit-appearance: none; 
    } 
    
    input[type="range"]::-webkit-slider-thumb { 
        height: 18px; 
        width: 18px; 
        border-radius: 70%; 
        background: #555; 
        pointer-events: auto; 
        -webkit-appearance: none; 
    } 
    
    </style>
    
    
    <script>
    const rangevalue = 
        document.querySelector(".slider-container .price-slider"); 
    const rangeInputvalue = 
        document.querySelectorAll(".range-input input"); 
    
    let priceGap = 500; 
    
    const priceInputvalue = 
        document.querySelectorAll(".price-input input"); 
    for (let i = 0; i < priceInputvalue.length; i++) { 
        priceInputvalue[i].addEventListener("input", e => { 
    
            let minp = parseInt(priceInputvalue[0].value); 
            let maxp = parseInt(priceInputvalue[1].value); 
            let diff = maxp - minp 
    
            if (minp < 0) { 
                alert("minimum price cannot be less than 0"); 
                priceInputvalue[0].value = 0; 
                minp = 0; 
            } 
    
            if (maxp > 10000) { 
                alert("maximum price cannot be greater than 10000"); 
    
                maxp = 10000; 
            } 
    
            if (minp > maxp - priceGap) { 
                priceInputvalue[0].value = maxp - priceGap; 
                minp = maxp - priceGap; 
    
                if (minp < 0) { 
                    priceInputvalue[0].value = 0; 
                    minp = 0; 
                } 
            } 
    
            if (diff >= priceGap && maxp <= rangeInputvalue[1].max) { 
                if (e.target.className === "min-input") { 
                    rangeInputvalue[0].value = minp; 
                    let value1 = rangeInputvalue[0].max; 
                    rangevalue.style.left = `${(minp / value1) * 100}%`; 
                } 
                else { 
                    rangeInputvalue[1].value = maxp; 
                    let value2 = rangeInputvalue[1].max; 
                    rangevalue.style.right = 
                        `${100 - (maxp / value2) * 100}%`; 
                } 
            } 
        }); 
    
        for (let i = 0; i < rangeInputvalue.length; i++) { 
            rangeInputvalue[i].addEventListener("input", e => { 
                let minVal = 
                    parseInt(rangeInputvalue[0].value); 
                let maxVal = 
                    parseInt(rangeInputvalue[1].value); 
    
                let diff = maxVal - minVal 
                
                if (diff < priceGap) { 
                
                    if (e.target.className === "min-range") { 
                        rangeInputvalue[0].value = maxVal - priceGap; 
                    } 
                    else { 
                        rangeInputvalue[1].value = minVal + priceGap; 
                    } 
                } 
                else { 
                    priceInputvalue[0].value = minVal; 
                    priceInputvalue[1].value = maxVal; 
                    rangevalue.style.left = 
                        `${(minVal / rangeInputvalue[0].max) * 100}%`; 
                    rangevalue.style.right = 
                        `${100 - (maxVal / rangeInputvalue[1].max) * 100}%`; 
                }
            }); 
        } 
    }
    </script>
    

                <button class="bg-dark" style="color:white; border-radius: 5px; margin-left: 10px; border: 0px; padding: 10px; padding-left: 20px; padding-right: 20px; margin-top: 20px;">Найти</button>

                <a class="bg-danger" style="color:white; border-radius: 5px; margin-left: 10px; border: 0px; padding: 10px; padding-left: 20px; padding-right: 20px; margin-top: 20px;" href="/catalogCosmetc">Сбросить</a>
    
            </form>
        </div>
        
    </div>
</div>




<input type="hidden" name="user_id" id="user_id" value="{{Auth::user() !== null? Auth::user()->id:null}}">
@csrf
    <!-- фильтрация -->



    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5 cards-block">
                @foreach ($products as $product)

                <div class="col-lg-4 col-md-6 @if($product->position>6) ordersHidden lastest @else ordersBlock @endif">
                    <div class="team-item">
                        <a href="{{action('App\Http\Controllers\MainController@showProduct', ['id' => $product->product_id])}}">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="/products/{{$product->photo}}" alt="" style="height: 350px; object-fit: cover;">
                            </div>
                        </a>
                        <h4 class="text-uppercase" style="margin-top: 15px;">{{$product->name}} <span style="color:gray;">{{round(($product->price/100) * (100-$stock))}} ₽</span>@if ($stock!=0)<span style='color: red; font-size: 60%;'>-{{$stock}}%</span>@endif</h4>
                        <h6>{{$product->brand}} - {{$product->texture}} {{$product->area}}</h6>
                        <div class='row'>
                            @if(Auth::user() && auth()->user()->is_blocked !=1)
                            <button class="addBasket bg-dark" data-id="{{$product->product_id}}" style="color:white; border-radius: 5px; border: 0px; padding: 10px; padding-left: 20px; padding-right: 20px; margin-top: 20px;">В корзину</button>
                            @elseif (Auth::user()  && auth()->user()->is_blocked ==1)
                            <h6 style='color:red;'>Вы заблокированы</h6>
                            @else
                            @endif
                        </div>
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
