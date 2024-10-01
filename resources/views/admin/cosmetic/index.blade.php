@extends('layouts.admin_layout')

@section('title', 'Все товары')

@section('content')
@if (session('success'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aira-hidden="true">x</button>
            <h4><i class="icon fa fa-check"> {{session('success')}}</i></h4>
        </div>
        @endif

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3><b>Скоро закончатся:</b></h3>
            </div>
        </div>
    </div>
</div>


<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                     <tr> <th style="width: 5%">ID</th>
                            <th> Название  </th>
                            <th> Бренд  </th>
                            <th> Количество  </th>
                            <th> Цена  </th>
                            <th> Дата добавления </th>
                          <th> Действие </th>
                          <th> Действие </th>
                          <th> Действие </th>
                          <th> Действие </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        @if ($product->count < 10)
                        <tr><td>{{$product->id}}</td>
                        <!-- <td>{{$product->name}} @if ($product->group_id!=null) ({{$product->group_name}}) @endif</td> -->
                        <td>{{$product->name}}</td>
                        <td>{{$product->brand}}</td>
                        <td>{{$product->count}}</td>
                        <td>{{$product->price}}</td>
                        <!-- <td>{{$product->count}}</td> -->
                        <td>{{$product->created_at}}</td>
                        <td>
                            <a href="{{route('cosmetics.show', $product->product_id)}}" class="btn = btn-sm" style="background-color: #4DD54F; color: white;">
                                Просмотр
                            </a>
                        </td>
                        <td>
                            <a href="{{route('comments.index', ['id' => $product->product_id])}}" class="btn = btn-sm" style="background-color: blue; color: white;">
                                Комментарии 
                            </a>
                        </td>
                        <td>
                        <a href="{{route('cosmetics.edit', ['cosmetic' => $product->product_id])}}" class="btn btn-info btn-sm">
                            <i class="fas fa-pencil-alt"></i>
                            Редактировать 
                        </a>
                        </td>
                        <td>
                        <form action="{{route('products.destroy', $product->product_id)}}" method="post" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm delete-btn" type="submit">
                                    <i class="fas fa-trash"></i>
                                    Удалить
                                </button>
                        </form>
                        </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>








<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3><b>Все товары:</b></h3>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                     <tr> <th style="width: 5%">ID</th>
                            <th> Название  </th>
                            <th> Бренд  </th>
                            <th> Количество  </th>
                            <th> Цена  </th>
                            <th> Дата добавления </th>
                          <th> Действие </th>
                          <th> Действие </th>
                          <th> Действие </th>
                          <th> Действие </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        @if ($product->count >= 10)
                        <tr><td>{{$product->product_id}}</td>
                        <td>{{$product->name}} </td>
                        <td>{{$product->brand}}</td>
                        <td>{{$product->count}}</td>
                        <td>{{$product->price}}</td>
                        <td>{{$product->created_at}}</td>
                        <td>
                            <a href="{{route('cosmetics.show', $product->product_id)}}" class="btn = btn-sm" style="background-color: #4DD54F; color: white;">
                                Просмотр
                            </a>
                        </td>
                        <td>
                            <a href="{{route('comments.index', ['id' => $product->product_id])}}" class="btn = btn-sm" style="background-color: blue; color: white;">
                                Комментарии 
                            </a>
                        </td>
                        <td>
                        <a href="{{route('cosmetics.edit', ['cosmetic' => $product->product_id])}}" class="btn btn-info btn-sm">
                            <i class="fas fa-pencil-alt"></i>
                            Редактировать 
                        </a>
                        </td>
                        <td>
                        <form action="{{route('products.destroy', $product->product_id)}}" method="post" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm delete-btn" type="submit">
                                    <i class="fas fa-trash"></i>
                                    Удалить
                                </button>
                        </form>
                        </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection