@extends('layouts.admin_layout')

@section('title', 'Все Акции')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Действующие акции</h1>
            </div>
        </div>
        @if (session('success'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aira-hidden="true">x</button>
            <h4><i class="icon fa fa-check">{{session('success')}}</i></h4>
        </div>
        @endif
    </div>
</div>



<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                     <tr> <th style="width: 5%">ID</th>
                          <th> Заголовок </th>
                          <th> Тип товаров </th>
                          <th> Процент скидки </th>
                          <th> Дата начала </th>
                          <th> Дата окончания </th>
                          <th> Действие </th>
                          <th> Действие </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stocks as $stock)
                            @if ($stock->end>=$date)
                            <tr><td>{{$stock->id}}</td>
                            <td>{{$stock->name}}</td>
                            <td>{{$stock->type}}</td>
                            <td>{{$stock->percent}}</td>
                            <td>{{$stock->start}}</td>
                            <td>{{$stock->end}}</td>

                            <td>
                            <a href="{{route('stocks.edit', $stock->id)}}" class="btn btn-info btn-sm">
                                <i class="fas fa-pencil-alt"></i>
                                Редактировать
                            </a>
                            </td>
                            <td>

                            <form action="{{route('stocks.destroy', $stock->id)}}" method="post" style="display: inline-block">
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
                <h1 class="m-0">Прошедшие акции</h1>
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
                          <th> Заголовок </th>
                          <th> Тип товаров </th>
                          <th> Процент скидки </th>
                          <th> Дата начала </th>
                          <th> Дата окончания </th>
                          <th> Действие </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stocks as $stock)
                            @if ($stock->end< date('Y-m-d'))
                            <tr><td>{{$stock->id}}</td>
                            <td>{{$stock->name}}</td>
                            <td>{{$stock->type}}</td>
                            <td>{{$stock->percent}}</td>
                            <td>{{$stock->start}}</td>
                            <td>{{$stock->end}}</td>


                            <td>

                            <form action="{{route('stocks.destroy', $stock->id)}}" method="post" style="display: inline-block">
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