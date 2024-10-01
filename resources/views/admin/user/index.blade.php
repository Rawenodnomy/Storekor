@extends('layouts.admin_layout')

@section('title', 'Все Пользователи')

@section('content')


<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Администраторы ресурса</h1>
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
                          <th> Имя  </th>
                          <th> Почта </th>
                          <th> Дата регистрации </th>
                          <th> Действие </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @if ($user->is_admin==1)
                                <tr><td>{{$user->user_id}}</td>
                                <td>{{$user->name}} </td>
                                <td>{{$user->email}} </td>
                                <td>{{$user->date_reg}} </td>

                                <td>
                                    <a href="{{route('users.show', $user->user_id)}}" class="btn btn-sm" style="background-color: #4DD54F; color: white;">
                                        Просмотр профиля
                                    </a>
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
                <h1 class="m-0">Все Пользователи</h1>
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
                          <th> Имя  </th>
                          <th> Почта </th>
                          <th> Статус </th>
                          <th> Кол-во заказов  </th>
                          <th> Дата регистрации </th>
                          <th> Действие </th>
                          <th> Действие </th>
                          <th> Действие </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @if ($user->is_admin!=1 && $user->is_blocked!=1)
                                <tr><td>{{$user->user_id}}</td>
                                <td>{{$user->name}} </td>
                                <td>{{$user->email}} </td>
                                
                                @if ($user->is_blocked == 1)
                                <td>Заблокирован</td>
                                @else
                                <td>Не в бане</td>
                                @endif
                                <td>{{$user->countOrders}} </td>
                                <td>{{$user->date_reg}} </td>

                                <td>
                                    <a href="{{route('users.show', $user->user_id)}}" class="btn btn-sm" style="background-color: #4DD54F; color: white;">
                                        Просмотр профиля
                                    </a>
                                </td>
                                <td>
                                <a href="{{route('users.edit', $user->user_id)}}" class="btn btn-info btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                    Комментарии пользователя
                                </a>
                                </td>
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
                <h1 class="m-0">Заблкоированные Пользователи</h1>
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
                          <th> Имя  </th>
                          <th> Почта </th>
                          <th> Статус </th>
                          <th> Кол-во заказов  </th>
                          <th> Дата регистрации </th>
                          <th> Действие </th>
                          <th> Действие </th>
                          <th> Действие </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @if ($user->is_blocked==1)
                                <tr><td>{{$user->user_id}}</td>
                                <td>{{$user->name}} </td>
                                <td>{{$user->email}} </td>
                                
                                @if ($user->is_blocked == 1)
                                <td>Заблокирован</td>
                                @else
                                <td>Не в бане</td>
                                @endif
                                <td>{{$user->countOrders}} </td>
                                <td>{{$user->date_reg}} </td>

                                <td>
                                    <a href="{{route('users.show', $user->user_id)}}" class="btn btn-sm" style="background-color: #4DD54F; color: white;">
                                        Просмотр профиля
                                    </a>
                                </td>
                                <td>
                                <a href="{{route('users.edit', $user->user_id)}}" class="btn btn-info btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                    Комментарии пользователя
                                </a>
                                </td>
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