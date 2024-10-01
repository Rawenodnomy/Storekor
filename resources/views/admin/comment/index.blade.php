@extends('layouts.admin_layout')

@section('title', 'Комментарии')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Комментарии</h1>
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
                            <th> Пользователь  </th>
                            <th> Товар  </th>
                            <th> Дата  </th>
                            <th> Комментарий  </th>
                          <th> Действие </th>
                          <th> Действие </th>
                          <th> Действие </th>
                          <th> Действие </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comments as $comment)
                        <tr><td>{{$comment->id}}</td>
                        <td>{{$comment->user_name}}</td>
                        <td>{{$comment->product_name}}</td>
                        <td>{{$comment->created_at}}</td>
                        <td>{{$comment->content}}</td>

                        <td>
                            <a href="{{route('comments.show', $comment->id)}}" class="btn = btn-sm" style="background-color: #4DD54F; color: white;">
                                Просмотр 
                            </a>
                        </td>
                        <td>
                        <a href="{{route('products.show', $comment->product_id)}}" class="btn btn-info btn-sm">
                            Перейти к товару 
                        </a>
                        </td>
                        <td>
                        <a href="{{route('users.show', $comment->user_id)}}" class="btn btn-info btn-sm" style='background-color: blue;'>
                            Перейти к пользователю 
                        </a>
                        </td>
                        <td>
                        <form action="{{route('comments.destroy', $comment->id)}}" method="post" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm delete-btn" type="submit">
                                    <i class="fas fa-trash"></i>
                                    Удалить
                                </button>
                        </form>
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