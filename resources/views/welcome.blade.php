@include('/layouts/header')


<div class="container-fluid bg-primary py-5 mb-5 hero-header">
        <div class="container py-5">
            <div class="row justify-content-start">
                <div class="col-lg-8 text-center text-lg-start" >
                    <h1 class="font-secondary text-primary mb-4">K-Shop</h1>
                    <h1 class="display-1 text-uppercase text-white mb-4 shadow">{{$company->name}}</h1>
                    <h1 class="text-uppercase text-white shadow">{{$company->slogan}}</h1>
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start pt-5">
                        <a href='/catalog' class="btn-play" >
                            <span></span>
                        </a>
                        <h5 class="font-weight-normal text-white m-0 ms-4 d-none d-sm-block">Перейти в каталог</h5>
                    </div>
                </div>
            </div>
        </div>
</div>




@foreach ($information as $about)
@if ($about->heading == "О нас")


<div class="container-fluid pt-5">
        <div class="container">
            <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
                <!-- <h2 class="text font-secondary">{{$about->heading}}</h2> -->
                <h1 class="display-4 text-uppercase">{{$about->heading}}</h1>
            </div>
            <div class="row gx-5">
                <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100" src="/info/{{$about->img}}" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-6 pb-5">
                    <h4 class="mb-4">{{$company->name}} - {{$company->slogan}}</h4>
                    <p class="mb-5">{{$about->text}}</p>
                    <div class="row g-2">
                        <h4>Так же можете прочитать:</h4>
                        <div>
                            @foreach ($information as $other)
                                <a href="{{action('App\Http\Controllers\MainController@getInfo', ['id' => $other->id])}}"><h4 class="text-uppercase">• {{$other->heading}} </h4></a>
                            @endforeach
                        </div>
                    </div>

                    
                    

                </div>
            </div>
        </div>
</div>
@endif
@endforeach

<br><br><br>

<div class="container-fluid bg-img py-5 mb-5" style='background-color: black;' id='blocktoscroll'>
        <div class="container py-5">
            <div class="row gx-5 gy-4">
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex">
                        <div class="bg-primary border-inner d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fa fa-star text-white"></i>
                        </div>
                        <div class="ps-4">
                            <h6 class="text-primary text-uppercase">Товаров</h6>
                            <h1 class="display-5 text-white mb-0" data-toggle="counter-up">{{$countProd->count}}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex">
                        <div class="bg-primary border-inner d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fa fa-check text-white"></i>
                        </div>
                        <div class="ps-4">
                            <h6 class="text-primary text-uppercase">Заказов</h6>
                            <h1 class="display-5 text-white mb-0" data-toggle="counter-up">{{$countOrder->count}}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex">
                        <div class="bg-primary border-inner d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fa fa-user text-white"></i>
                        </div>
                        <div class="ps-4">
                            <h6 class="text-primary text-uppercase">Пользователей</h6>
                            <h1 class="display-5 text-white mb-0" data-toggle="counter-up">{{$countUser->count}}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex">
                        <div class="bg-primary border-inner d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fa fa-users text-white"></i>
                        </div>
                        <div class="ps-4">
                            <h6 class="text-primary text-uppercase">Групп</h6>
                            <h1 class="display-5 text-white mb-0" data-toggle="counter-up">{{$countGroup->count}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="container-fluid py-5">
        <div class="container">
        <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
                <h1 class="display-4 text-uppercase">Новости</h1>
            </div>
            <div class="row g-5">
                @foreach ($news as $new)
                    <div class="col-md-5 col-md-6">
                    <a href="/getNew/{{$new->id}}">
                        <div class="team-item">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid w-100 img-news" src="/news/{{$new->image}}" alt="" style='max-height: 300px; object-fit: cover;'>
                            </div>
                            <div class="bg-dark border-inner text-center p-4 border-new">
                                <h4 class="text-uppercase text-white">{{$new->title}}</h4>
                                <!-- <p class="text-white m-0">{{$new->content}}</p> -->
                            </div>
                        </div>
                    </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <div class="col-lg-12 col-md-6 text-center" style="margin-left: auto; margin-right: auto; margin-top: 50px; margin-bottom: 50px;">
        <a href="/getNews" class="btn btn-primary border-inner py-3 px-5">Все новости</a>
    </div>



    @if ($stocks !=[])
    <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style='padding-bottom: -20px;' >
                <!-- <h2 class="text-primary font-secondary">Team Members</h2> -->
        <h1 class="display-4 text-uppercase">Действующие акции</h1>
    </div>

    <div class="container-fluid service position-relative px-5" style="margin-top: 90px; margin-bottom: 90px; ">
        
        <div class="container">
            <div class="row g-5">
                @foreach ($stocks as $stock)
                <div class="col-lg-4 col-md-6" >
                    <div class="bg-primary border-inner text-center text-white p-5" >
                        <h4 class="text-uppercase mb-3">{{$stock->name}}</h4>
                        <p><span style='color:black'>С {{$stock->start}} по {{$stock->end}}</span></p>
                        <a class="text-uppercase text-dark" href="@if ($stock->dire=='kpop') {{action('App\Http\Controllers\MainController@getProductsByType', ['id' => $stock->type_id])}} @else /catalogCosmetc @endif"><b>Перейти в каталог <i class="fa fa-arrow-right"></i></b></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif




<div class="container-fluid py-5">
        <div class="container">
            <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
                <!-- <h2 class="text-primary font-secondary">Team Members</h2> -->
                <h1 class="display-4 text-uppercase">Популярные группы на сайте</h1>
            </div>
            <div class="row g-5">

                @foreach ($groups as $group)
                   
                        <div class="col-lg-4 col-md-6">
                        <a href="{{action('App\Http\Controllers\MainController@getProductsByGroup', ['id' => $group->id])}}">
                            <div class="team-item" style=" cursor: pointer">
                                <div class="position-relative overflow-hidden">
                                    <img class="img-fluid" src="/groups/{{$group->photo_group}}" alt="" style='height: 250px; width: 500px; object-fit: cover;'>
                                </div>
                                    <h4 class="text-uppercase">{{$group->group_name}}</h4>
                            </div>
                            </a>   
                        </div> 
                                
                @endforeach
             
            </div>
        </div>
</div>




    <section class="tm-banner-section" id="tmVideoSection">
      <video id="hero-vid" autoplay="" loop="" muted>
        
      </video>
    </section>
    <script src="/assets/jq/jquery-1.9.1.min.js"></script>
    <script src="/assets/jq/jquery.singlePageNav.min.js"></script>
    <script>

      function detectIE() {
        var ua = window.navigator.userAgent;

        var msie = ua.indexOf("MSIE ");
        if (msie > 0) {
          return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)), 10);
        }

        var trident = ua.indexOf("Trident/");
        if (trident > 0) {
          var rv = ua.indexOf("rv:");
          return parseInt(ua.substring(rv + 3, ua.indexOf(".", rv)), 10);
        }


        return false;
      }

      function setVideoHeight() {
        const videoRatio = 1920 / 1080;
        const minVideoWidth = 400 * videoRatio;
        let secWidth = 0,
          secHeight = 0;

        secWidth = videoSec.width();
        secHeight = videoSec.height();

        secHeight = secWidth / 2.13;

        if (secHeight > 600) {
          secHeight = 600;
        } else if (secHeight < 400) {
          secHeight = 400;
        }

        if (secWidth > minVideoWidth) {
          $("video").width(secWidth);
        } else {
          $("video").width(minVideoWidth);
        }

        videoSec.height(secHeight);
      }

     




      $(function() {
        if (detectIE()) {
          alert(
            "Please use the latest version of Edge, Chrome, or Firefox for best browsing experience."
          );
        }



        videoSec = $("#tmVideoSection");



        setVideoHeight();


      });
    </script>


<br>
<div class="container-fluid about py-5">
        <div class="container">
            <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
                <h1 class="display-4 text-uppercase">Новинки на сайте</h1>
            </div>
            <div class="tab-class text-center">
                <ul class="nav nav-pills d-inline-flex justify-content-center bg-dark text-uppercase border-inner p-4 mb-5">
                    
                    @foreach ($types as $type)
                        <li class="nav-item hover">
                            <a class="nav-link text-white  @if ($type->id==3) active @endif" data-bs-toggle="pill" href="#{{$type->type_products}}">{{$type->type_products}}</a>
                        </li>
                    @endforeach

                </ul>

                
           
                <div class="tab-content">
                @foreach ($types as $type)
                    <div id="{{$type->type_products}}" class="tab-pane fade show p-0 @if ($type->id==3) active @endif" >
                        <div class="row g-3">
                        @foreach ($newProducts as $product)
                                @if ($product->type_id == $type->id)
                                    <div class="col-lg-6">

                                            <a href="{{action('App\Http\Controllers\MainController@showProduct', ['id' => $product->id])}}">
                                            <div class="d-flex h-100">
                                                <div class="flex-shrink-0 blockforimg" id='{{$product->photo}}'>
                                                    
                                                </div>
                                                <div class="d-flex descnew flex-column justify-content-center text-start bg-secondary border-inner px-4">
                                                    <h5 class="text-uppercase">{{$product->name}} <span style='color: gray;'>{{$product->price}}₽</span></h5>

                                                    @if ($product->group_id!=null)
                                                        <span>{{$product->group_name}}</span>
                                                    @else
                                                        <span>{{$product->category}}</span>
                                                    @endif
                                                    <!-- <span style='color: black;'>{{$product->description}}</span> -->
                                                </div>
                                            </div>
                                            </a>

                                    </div>
                                @endif
                        @endforeach
                        </div>
                    </div>
                @endforeach

                </div>
            </div>
        </div>
    </div>





<script>
function loadVideoWhenVisible() {
    var blocktoscroll = document.getElementById('blocktoscroll');
    var videoPlaceholder = document.getElementById('hero-vid');
    var rect = blocktoscroll.getBoundingClientRect();

    if (rect.top <= window.innerHeight && rect.bottom >= 0) {
        videoPlaceholder.innerHTML = ' <source src="/videos/{{$company->video}}" type="video/mp4" />';
        
        window.removeEventListener('scroll', loadVideoWhenVisible);
    }
}

window.addEventListener('scroll', loadVideoWhenVisible);
document.addEventListener('DOMContentLoaded', loadVideoWhenVisible);
</script>
<script>
function loadprod() {

    var blocktoscroll = document.getElementById('blocktoscroll');
    var blockforimg = document.querySelectorAll('.blockforimg');
    var rect = blocktoscroll.getBoundingClientRect();

    if (rect.top <= window.innerHeight && rect.bottom >= 0) {
    blockforimg.forEach(item => {
        item.innerHTML = `<img class="img-fluid" src="/products/${item.id}" alt="" style="width: 150px; height: 150px; object-fit: cover;">`
        window.removeEventListener('scroll', loadprod);
    })
}


}

window.addEventListener('scroll', loadprod);

document.addEventListener('DOMContentLoaded', loadprod);
</script>

    
        
@include('/layouts/footer')