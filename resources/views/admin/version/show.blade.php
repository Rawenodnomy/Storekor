@extends('layouts.admin_layout')

@section('title', 'Просмотр Версии')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Просмотр Версии</h1>
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
                                <h4 class="form-control">{{$version->version_name}}</h4>

                                <label for="exampleInputCategory">Товар</label>
                                <h4 class="form-control">{{$vers}}</h4>

                                <label for="exampleInputCategory">Количество</label>
                                <h4 class="form-control">{{$version->count}}</h4>
                                
                                <br>
                                
                            </div>
                        </div>


                </div>
            </div>
        </div>
    </div>
</section>




@endsection