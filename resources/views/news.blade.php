@include('/layouts/header')



    <div class="container-fluid py-5">
        <div class="container">
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
                                </div>
                            </div>
                        </a>
                    </div>
                    
                @endforeach
            </div>
        </div>
    </div>





@include('/layouts/footer')
