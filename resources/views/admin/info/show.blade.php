@extends('layouts.admin_layout')

@section('title', 'Инфоблок')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Инфоблок: {{$info->heading}}</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
        @if (session('success'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aira-hidden="true">x</button>
            <h4><i class="icon fa fa-check"> {{session('success')}}</i></h4>
        </div>
        @endif
      </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputCategory">Название</label>
                                <h4 class="form-control">{{$info->heading}}</h4>
                                <label for="exampleInputCategory">Описание</label>
                                <p style='border: 1px #c0c0c0 solid; padding: 5px; padding-left: 10px; padding-right: 10px; border-radius: 5px;'>{{$info->text}}</p>
                                <br>
                                <label for="exampleInputCategory">Фото</label>
                                <br>
                                <img style='height: 300px;' src="/info/{{$info->img}}" alt="">
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>




@endsection

    <script>

        function handleSelectChange(event) {

        var div = document.getElementById('count_product');

        var selectElement = event.target;

        var value = selectElement.value;

        if (value==3) {
            div.style.display = 'none';
        } else {
            div.style.display = 'block';
        }

        }

    </script>