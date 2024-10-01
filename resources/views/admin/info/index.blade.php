@extends('layouts.admin_layout')

@section('title', 'Все Инфоблоки')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Все Инфоблоки</h1>
            </div>
            
        </div>
        @if (session('success'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aira-hidden="true">x</button>
            <h4><i class="icon fa fa-check"> {{session('success')}}</i></h4>
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
                          <th> Название  </th>
                          <th> Фото </th>
                          <th> Текст  </th>
                          <th> Действие </th>
                          <th> Действие </th>
                          <th> Действие </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($infos as $info)
                        <tr><td>{{$info->id}}</td>
                        <td>{{$info->heading}}</td>

                        <td><img src="/info/{{$info->img}}" alt="{{$info->img}}" style='width: 300px;'></td>
                        <td>{{$info->text}} </td>

                        <td>
                            <a href="{{route('infos.show', $info->id)}}" class="btn  btn-sm" style="background-color: #4DD54F; color: white;">
                                Просмотр
                            </a>
                        </td>
                        <td>
                        <a href="{{route('infos.edit', $info->id)}}" class="btn btn-info btn-sm">
                            <i class="fas fa-pencil-alt"></i>
                            Редактировать
                        </a>
                        </td>
                        <td>
                        <form action="{{route('infos.destroy', $info->id)}}" method="post" style="display: inline-block">
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