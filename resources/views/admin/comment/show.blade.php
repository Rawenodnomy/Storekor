@extends('layouts.admin_layout')

@section('title', 'Просмотр комментария')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Просмотр комментария</h1>
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
                                <label for="exampleInputCategory">Пользователь</label>
                                <h4 class="form-control">{{$comment->user_name}}</h4>
                                <label for="exampleInputCategory">Дата</label>
                                <h4 class="form-control">{{$comment->created_at}}</h4>
                                <label for="exampleInputCategory">Товар</label>
                                <h4 class="form-control">{{$comment->product_name}}</h4>
                                <img style='height: 300px;' src="/products/{{$comment->product_photo}}" alt="">
                                <br><br>
                                <label for="exampleInputCategory">Комментарий</label>
                                <p style='border: 1px #c0c0c0 solid; padding: 5px; padding-left: 10px; padding-right: 10px; border-radius: 5px;'>{{$comment->content}}</p>

                                @if ($comment->photo!=null)
                                <label for="">Фото комментария</label><br>
                                <img src="/comments/{{$comment->photo}}" alt="" style='width: 200px;'><br><br>
                                @endif
                           

                                <form action="{{route('comments.destroy', $comment->id)}}" method="post" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm delete-btn" type="submit">
                                    <i class="fas fa-trash"></i>
                                    Удалить
                                </button>
                                </form>

                            </div>
                        </div>


                </div>
            </div>
        </div>
    </div>
</section>




@endsection