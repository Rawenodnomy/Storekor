@include('/layouts/header')



<div class="container-fluid pt-5">
        <div class="container">

            <div class="row gx-5">
                <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100" src="/news/{{$new->image}}" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-6 pb-5">
                    <h4 class="mb-4">{{$new->title}}</h4>
                    <p class="mb-5" style="white-space: pre-wrap;">{{$new->content}} </p>
                    <span style='color: gray;'>{{$new->created_at}}</span>
                </div>
            </div>
        </div>
</div>


@include('/layouts/footer')