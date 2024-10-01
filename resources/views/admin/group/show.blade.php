@extends('layouts.admin_layout')

@section('title', 'Просмотр группы')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Добавить Товар</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->

      </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputCategory">Название</label>
                                <h4 class="form-control">{{$product->name}}</h4>


                                
                                <label for="exampleInputCategory">Описание</label>
                                <p style='border: 1px #c0c0c0 solid; padding: 5px; padding-left: 10px; padding-right: 10px; border-radius: 5px;'>{{$product->description}}</p>
                              
                                <label for="exampleInputCategory">Фото</label>
                                <br>
                                <img style='height: 300px;' src="/products/{{$product->photo}}" alt="">

                            </div>
                        </div>


                </div>
            </div>
        </div>
    </div>
</section>




@endsection