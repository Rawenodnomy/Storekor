@extends('layouts.admin_layout')

@section('title', 'Все товары')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Все версии</h1>
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
                          <th> Название Версии </th>
                          <th> Альбом </th>
                          <th> Количество </th>
                          <th> Действие </th>
                          <th> Действие </th>
                          <th> Действие </th>
                          <th> Действие </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($versions as $version)
                        <tr><td>{{$version->id}}</td>
                        <td>{{$version->version_name}}</td>
                        <td>{{$version->name}}</td>
                        <td>{{$version->count}}</td>
                        <td>
                            <a href="{{route('versions.show', $version->id)}}" class="btn btn-sm" style="background-color: #4DD54F; color: white;">
                                Просмотр версии
                            </a>
                        </td>
                        <td>
                            <a href="{{route('products.show', $version->product_id)}}" class="btn btn-sm" style="background-color: #4DD54F; color: white;">
                                Перейти к товару
                            </a>
                        </td>
                        <td>
                        <a href="{{route('versions.edit', $version->id)}}" class="btn btn-info btn-sm">
                            <i class="fas fa-pencil-alt"></i>
                            Редактировать
                        </a>
                        </td>
                        <td>
                        <!-- <a href="" class="btn-danger btn-sm delete-btn">
                            <i class="fas fa-trash"></i>
                            Удалить
                        </a> -->
                        <form action="{{route('versions.destroy', $version->id)}}" method="post" style="display: inline-block">
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