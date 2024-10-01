@extends('layouts.admin_layout')

@section('title', 'Инфоблок')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">


        </div><!-- /.row -->
        @if (session('success'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aira-hidden="true">x</button>
            <h4><i class="icon fa fa-check">{{session('success')}}</i></h4>
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
                                <label for="exampleInputCategory">Имя</label>
                                <h4 class="form-control">{{$user->name}}</h4>
                                <label for="exampleInputCategory">Фото профиля</label><br>
                                <img style='height: 100px;' src="/users/{{$user->avatar}}" alt=""><br><br>
                                <label for="exampleInputCategory">Дата регистрации</label>
                                <h4 class="form-control">{{$user->date_reg}}</h4>
                                <label for="exampleInputCategory">Почта</label>
                                <h4 class="form-control">{{$user->email}}</h4>
                                <label for="exampleInputCategory">Статус</label>
                                @if ($user->is_blocked == 1)
                                <h4 class="form-control">Заблокирован</h4>
                                @else
                                <h4 class="form-control">Не в бане</h4>
                                @endif
                                <label for="exampleInputCategory">Данные для доставки</label>
                                <p style='border: 1px #c0c0c0 solid; padding: 5px; padding-left: 10px; padding-right: 10px; border-radius: 5px;'>
                                Адрес: {{$user->city}}, {{$user->address}} (Индекс почты: {{$user->index_mail}})
                                <br>
                                Получатель: {{$user->FIO}}
                                </p>

                                <td>
                                <form action="{{route('users.destroy', $user->user_id)}}" method="post" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                @if ($user->is_blocked == 1)
                                <button class="btn btn-sm" style="background-color: #4DD54F; color: white;" type="submit">
                                    Разбанить
                                </button>
                                @else
                                <button class="btn btn-danger btn-sm delete-btn" type="submit">
                                    Забанить
                                </button>
                                @endif

                        </form>

                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                     <tr> <th style="width: 5%">ID</th>
                          <th> Цена  </th>
                          <th> Кол-во товаров </th>
                          <th> Статус </th>
                          <th> Комментарий  </th>
                          <th> Дата заказа </th>
                          <th> Действие</th>


                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                        <td>{{$order->id}}</td>
                        <td>{{$order->price}} </td>
                        <td>{{$order->count}} </td>
                        <td>{{$order->stage}} </td>
                        <td>{{$order->comment}} </td>
                        <td>{{$order->created_at}} </td>
              
                        <td>
                            <a href="/admin/orders/{{$order->id}}" class="btn  btn-sm" style="background-color: #4DD54F; color: white;">
                                Просмотр
                            </a>
                        </td>
                     
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>





@endsection
