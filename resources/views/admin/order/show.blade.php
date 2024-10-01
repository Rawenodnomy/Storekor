@extends('layouts.admin_layout')

@section('title', 'Все товары')

@section('content')

<br>
@if (session('success'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aira-hidden="true">x</button>
            <h4><i class="icon fa fa-check">{{session('success')}}</i></h4>
        </div>
@endif

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                        
                                    <div class="row mb-2">
                                        <div class="col-sm-6">
                                            <h1 class="m-0">Заказ №: {{$order->id}}</h1>
                                            <br>
                                            <div style='border: 1px #c0c0c0 solid; padding: 5px; padding-left: 10px; padding-right: 10px; border-radius: 5px;'>
                                            <p>Заказ от: {{$order->created_at}}</p>
                                            <h5>{{$order->total_count}} товар на общую сумму {{$order->price}} ₽</h5>
                                            <p>Комментарий к заказу: <br>
                                            {{$order->comment}}
                                            </p>
                                            </div>
                                        </div>
                                    </div>
                   

                                <label for="exampleInputCategory">Данные для доставки</label>
                                <p style='border: 1px #c0c0c0 solid; padding: 5px; padding-left: 10px; padding-right: 10px; border-radius: 5px;'>
                                Адрес: {{$user->city}}, {{$user->address}} (Индекс почты: {{$user->index_mail}})
                                <br>
                                Получатель: {{$user->FIO}}
                                </p>



                            </div>
                            @if($order->status_id == 1)
                                <form action="{{route('orders.destroy', $order->id)}}" method="post" style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger delete-btn" type="submit">
                                            <i class="fas fa-trash"></i>
                                            Отменить
                                        </button>
                                </form>
                            @endif
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
                            <th> Товар </th>
                            <th> Фото </th>
                            <th> Тип  </th>
                            <th> Цена </th>
                            <th> Количество </th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order_products as $product)
                        <tr><td>{{$product->product_id}}</td>
                        <td>{{$product->name}} @if ($product->group_name!=null) ({{$product->group_name}}) @endif @if ($product->version_name!=null) [{{$product->version_name}}] @endif</td>
                        <td>
                            <img src="/products/{{$product->photo}}" alt="{{$product->photo}}" style='height: 100px;'>
                        </td>
                        <td>{{$product->type_name}}</td>
                        <td>{{$product->price}} ₽</td>
                        <td>{{$product->count_order}}</td>


                        <td>
                            <a href="{{route('products.show', $product->product_id)}}" class="btn btn-info btn-sm" style="background-color: #4DD54F;">
                            Просмотр товара
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










<div class="card-body">
    <div class="form-group">
        <form action="{{route('orders.update', $order->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @if($order->status_id == 1)

            <div class="card-footer">
            <label for="trackcode">Трек-код</label>
            <input type="text" name="track" class="form-control" id='trackcode' placeholder="Введите трек-код">
            <p><i style='font-size: 80%;'>Трек-код Почты России состоит из 14 цифр</i></p>
            <input type="hidden" name="order_id" value='{{$order->id}}'>
                <button type="submit" id='sbor' name='status' value='{{$order->status_id}}' disabled class="btn btn-primary">Отправить на сборку</button>
            </div>
            @elseif ($order->status_id == 2)
            <input type="hidden" name="order_id" value='{{$order->id}}'>
            <div class="card-footer">
                <button type="submit" name='status' value='{{$order->status_id}}' class="btn btn-primary">Заказ отправлен</button>
            </div>
            @elseif ($order->status_id == 3)
            <input type="hidden" name="order_id" value='{{$order->id}}'>
            <div class="card-footer">
                <button type="submit" name='status' value='{{$order->status_id}}' class="btn btn-primary">Завершить заказ</button>
            </div>
            @elseif ($order->status_id == 6)
            <h4>Заказ был отмненен</h4>
            @else
            <h4>Заказ был успешно доставлен</h4>
            @endif

        </form>

    </div>
    
</div>



<script>
var reg = /^[0-9]{14}$/gm;
const sb = document.getElementById('sbor');
let track = document.getElementById('trackcode');


track.addEventListener('input', function(){
    if (track.value.match(reg)){
        sb.disabled = false;
    } else {
        sb.disabled = true;
    }
})
</script>


@endsection