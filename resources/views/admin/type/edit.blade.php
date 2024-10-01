@extends('layouts.admin_layout')

@section('title', 'Обновить тип')

@section('content')



<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0">Обновить тип: {{$type['type_products']}}</h1>
          </div>
        </div>
        @if (session('success'))
        <div class="alert alert-success" role="alert" style='background-color: rgb(199, 0, 0); border: 1px red solid;'>
            <button type="button" class="close" data-dismiss="alert" aira-hidden="true" style='color: white;'>x</button>
            <h4><i class="icon fa fa-ban"> {{session('success')}}</i></h4>
        </div>
        @endif
      </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary">
                <form action="{{route('types.update', $type->id)}}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputCategory">Название</label>
                            <input type="text" name="title" value="{{$type->type_products}}" class="form-control" id="exampleInputCategory" placeholder="Введите название категории">
                            <input type="hidden" name='id' value="{{$type->id}}">
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