@extends('layouts.admin_layout')

@section('title', 'Просмотр Новости')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Просмотр Новости</h1>
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
                                <label for="exampleInputCategory">Заголовок</label>
                                <h4 class="form-control">{{$new->title}}</h4>

                                <label for="exampleInputCategory">Дата</label>
                                <h4 class="form-control">{{$new->created_at}}</h4>

                                <label for="exampleInputCategory">Текст статьи</label>
                                <p style='border: 1px #c0c0c0 solid; padding: 5px; padding-left: 10px; padding-right: 10px; border-radius: 5px; white-space: pre-wrap;'>{{$new->content}}</p>
                                <br>
                                <label for="exampleInputCategory">Фото</label>
                                <br>
                                <img style='height: 300px;' src="/news/{{$new->image}}" alt="">
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>




@endsection