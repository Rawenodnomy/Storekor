@include('/layouts/header')



<div class="container-fluid py-5">
        <div class="container">
            <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
                <!-- <h2 class="text-primary font-secondary">Team Members</h2> -->
                <h1 class="display-4 text-uppercase"> {{$what}} на сайте</h1>
            </div>
            <div class="row g-5">

                @foreach ($groups as $group)
                        <div class="col-lg-4 col-md-6">
                        <a href="@if ($what=='Группы') {{action('App\Http\Controllers\MainController@getProductsByGroup', ['id' => $group->id])}} @else /getProductsByBrand/{{$group->id}} @endif">
                            <div class="team-item" style=" cursor: pointer">
                                <div class="position-relative overflow-hidden">
                                    
                                    <img class="img-fluid" src="@if ($what=='Группы') /groups/{{$group->photo_group}} @else /brands/{{$group->photo}} @endif" alt="" style='height: 250px; width: 500px; object-fit: cover;'>
                                </div>
                                    <h4 class="text-uppercase">@if ($what=='Группы') {{$group->group_name}} @else {{$group->name}} @endif</h4>
                            </div>
                            </a>   
                        </div> 
                                
                @endforeach
             
            </div>
        </div>
</div>

@include('/layouts/footer')