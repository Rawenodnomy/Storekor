@extends('layouts.admin_layout')

@section('title', 'Добавить статью')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Добавить Группу</h1>
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
                    <form action="{{route('groups.store')}}" method="post" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputCategory">Название</label>
                                <input type="text" name="title" class="form-control" id="exampleInputCategory" placeholder="Введите название группы">
                                <br>
                                <label for="exampleInputCategory">На главную</label>
                                <select class="form-control" id="exampleInputCategory" name="main" id="main">
                                    <option value="0">Нет</option>
                                    <option value="1">Да</option>
                                </select>
                                <br>
                                <label for="file-upload" class="file-input">
                                Добавить фото группы
                                <input id="file-upload" type="file" name='img' onchange="previewImage(event)">
                                </label>

                                <div class="image-preview" id="image-preview">
                                <img id="preview" style="max-width: 250px;">
                                </div>
                                <br>
                                <label for="exampleInputCategory">Описание группы</label>
                                <textarea name="text" id="text" class="form-control"></textarea>

                                
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.file-input {
  position: relative;
  overflow: hidden;
  display: inline-block;
  cursor: pointer;
  border: 1px solid #ccc;
  padding: 6px 12px;
  border-radius: 4px;
  background-color: #f1f1f1;
}

.file-input input[type="file"] {
  position: absolute;
  font-size: 100px;
  opacity: 0;
  right: 0;
  top: 0;
}

.file-input:hover {
  background-color: #e9e9e9;
}

.image-preview {
  margin-top: 20px;
  display: none;
}

.image-preview img {
  max-width: 100%;
  height: auto;
}
</style>


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
    
    var reader = new FileReader();
    reader.onload = function(){
      var img = document.getElementById('preview');
      img.src = reader.result;
      document.getElementById('image-preview').style.display = 'block';
    };
    reader.readAsDataURL(file);
  }
}

    </script>


@endsection