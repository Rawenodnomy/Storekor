@include('/layouts/header')


@foreach ($information as $info)
@if ($info->id == $what)


<div class="container-fluid pt-5">
        <div class="container">
            <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">

                <h1 class="display-4 text-uppercase">{{$info->heading}}</h1>
            </div>
            <div class="row gx-5">
                <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100" src="/info/{{$info->img}}" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-6 pb-5">
                    <h4 class="mb-4">STOREKOR - {{$info->heading}}</h4>
                    <p class="mb-5 prewrap">{{$info->text}}</p>
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


@include('/layouts/footer')