@extends('layouts.admin_layout')

@section('title', 'Обновить Инфоблок')


@section('content')

<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Обновить Инфоблок</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
        @if (session('success'))
        <div class="alert alert-success" role="alert" style='background-color: rgb(199, 0, 0); border: 1px red solid;'>
            <button type="button" class="close" data-dismiss="alert" aira-hidden="true" style='color: white;'>x</button>
            <h4><i class="icon fa fa-ban"> {{session('success')}}</i></h4>
        </div>
        @endif
      </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary">
                    <form action="{{route('infos.update', $info->id)}}" method="post" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputCategory">Название</label>
                                <input type="text" name="title" class="form-control" id="exampleInputCategory" value='{{$info->heading}}' placeholder="Введите название инфоблока">
                                <!-- <label for="exampleInputCategory">Фото инфоблока</label>
                                <input type="file" name="img" class="form-control" id="exampleInputCategory" required> -->
                                <br>

                                <label for="exampleInputCategory">Содержание инфоблока</label>
                                <p class="form-control" style='height: 100%'>{{$info->text}}</p>
                                <input type="hidden" name="oldtext" value='{{$info->text}}'>
                                <input type="hidden" name="id" value='{{$info->id}}'>
                                <label for="exampleInputCategory">Новое содержание инфоблока</label>
                                <textarea name="text" id="text" class="form-control" ></textarea>
                                <br>
                                <label for="newphoto">
                                <img id='changephoto' src="/info/{{$info->img}}" alt="{{$info->img}}" style='height: 350px'>
                                </label>
                                <input onchange="previewImage(event)" id="newphoto" name='img' type="file" style='display: none;'/>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Обновить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<script>

function previewImage(event) {
  var input = event.target;
  
  if (input.files && input.files[0]) {
    var file = input.files[0];
    var fileSize = file.size / 1024 / 1024; // Size in MB
    
    if (fileSize > 2) {
      alert("Файл должен весить менее 2 мб.");
      return;
    }
    
    if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
      alert("Допускаются только файлы с расширением .JPG, .JPEG, .PNG до 10 мб включительно");
      return;
    }
    
        if (file) {
            const [file] = newphoto.files
            changephoto.src = URL.createObjectURL(file)
            changephoto.classList.add("changephoto");
        }
}


    
}


</script>


@endsection

