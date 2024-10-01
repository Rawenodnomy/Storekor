@extends('layouts.admin_layout')

@section('title', 'Все товары')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Заказы в статусе: {{$stage->stage}}</h1>
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
                            <th> Статус  </th>
                            <th> Цена  </th>
                            <th> Кол-во товаров  </th>
                            <th> Дата  </th>
                            <!-- <th> Количество  </th> -->
                            <th>Комментарий </th>
                          <!-- <th style="width: 30%"> </th> -->
                          <th> Действие </th>
 
                          <th> Действие </th>
 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr><td>{{$order->id}}</td>
                        <td>{{$order->stage}}</td>
                        <td>{{$order->price}}</td>
                        <td>{{$order->count}}</td>
                        <td>{{$order->created_at}}</td>
                        <td>{{$order->comment}}</td>
                        <td>
                            <a href="{{route('orders.show', $order->id)}}" class="btn btn-info " style="background-color: #4DD54F;">
                            Просмотр
                            </a>
                        </td>
                        @if ($order->status_id == 1)
                        <td>
                        <form action="{{route('orders.destroy', $order->id)}}" method="post" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger delete-btn" type="submit">
                                    <i class="fas fa-trash"></i>
                                    Отменить
                                </button>
                        </form>
                        </td>
                        @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection