@extends('layouts.admin_layout')

@section('title', 'Просмотр Товара')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Просмотр Товара</h1>
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
                                <h4 class="form-control">{{$product->name}}</h4>
                                <label for="exampleInputCategory">Цена</label>
                                <h4 class="form-control">{{$product->price}}</h4>
                                <label for="exampleInputCategory">Категория</label>
                                <h4 class="form-control">{{$product->category}}</h4>
                                
                                <label for="exampleInputCategory">Вес</label>
                                <h4 class="form-control">{{$product->weight}}</h4>

                                <label for="exampleInputCategory">Количество</label>
                                <h4 class="form-control">{{$product->count}}</h4>

                                <label for="exampleInputCategory">Описание</label>
                                <p style='border: 1px #c0c0c0 solid; padding: 5px; padding-left: 10px; padding-right: 10px; border-radius: 5px;'>{{$product->description}}</p>
                                <br>
                                <label for="exampleInputCategory">Фото</label>
                                <br>
                                <img style='height: 300px;' src="/products/{{$product->photo}}" alt="">
                               
                                <!-- <input type="number" name="count" class="form-control" id="exampleInputCategory" value="{{$product->count}}">  -->
                            </div>
                        </div>


                </div>
            </div>
        </div>
    </div>
</section>




@endsection