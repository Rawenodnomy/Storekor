@extends('layouts.admin_layout')

@section('title', 'Обновить Версию')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Обновить Версию</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
        @if (session('success'))
        <div class="alert alert-success" role="alert" style='background-color: rgb(199, 0, 0); border: 1px red solid;'>
            <button type="button" class="close" data-dismiss="alert" aira-hidden="true" style='color: white;'>x</button>
            <h4><i class="icon fa fa-ban"> {{session('success')}}</i></h4>
        </div>
        @endif
      </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary">
                    <form action="{{route('versions.update', $version->id)}}" method="post" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputCategory">Название</label>
                                <input type="text" name="title" class="form-control" id="exampleInputCategory" value = '{{$version->version_name}}' placeholder="Введите название товара">
                                <br>
                                <label for="exampleInputCategory">Товар</label>
                                <br>
                                <select onchange="handleSelectChange(event)" class="form-control" id="exampleInputCategory" name="product_id" id="asd">
                                    <option value="{{$vers->product_id}}">{{$vers->album_name}}</option>
                                    @foreach ($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </select>
                               <br>
                                <div id='count_product' >
                                <label for="exampleInputCategory">Количество</label>
                                <input type="number" name="count" class="form-control" value='{{$version->count}}' id="exampleInputCategory">
                                </div>
                                
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Обновить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>




@endsection