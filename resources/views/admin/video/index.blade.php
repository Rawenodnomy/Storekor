@extends('layouts.admin_layout')

@section('title', 'Обновить Видео')


@section('content')

<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Обновить Видео</h1>
          </div><!-- /.col -->

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
                    <form action="{{route('video.update', 1)}}" method="post" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputCategory">Заглавное видео</label>
                                <br>
                                <video controls width="550">
                                <source src="/videos/{{$video}}" type="video/webm" />
                                <source src="/videos/{{$video}}" type="video/mp4" />
                                </video>
                            </div>
                            <input type="hidden" name="old" value='{{$video}}' accept="video/*">
                        
                        <label for="video-upload" class="file-input">
                            Добавить видео
                        <input id="video-upload" type="file" name="new" accept=".mp4" onchange="previewVideo(event)">
                        </label>

                        <div class="video-preview" id="video-preview" style='display:none;'>
                            <video width="550" controls id="preview"></video>
                        </div>

                        <div class="card-footer">
                            <button type="submit" id='btnup' class="btn btn-primary" disabled>Обновить</button>
                        </div>
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

.video-preview {
  margin-top: 20px;
}

.video-preview video {
  max-width: 100%;
}
</style>

<script>
function previewVideo(event) {
  var input = event.target;
  
  if (input.files && input.files[0]) {
    var file = input.files[0];
    var fileSize = file.size / 1024 / 1024; // Size in MB
    
    if (fileSize > 2) {
      alert("Размер видео не должен привышать 2 мб");
      return;
    }
    
    if (file.type !== 'video/mp4') {
      alert("Допускаются только видео");
      return;
    }
    
    var reader = new FileReader();
    reader.onload = function(){
      var video = document.getElementById('preview');
      var btn = document.getElementById('btnup');
      video.src = reader.result;
      btn.disabled=false
      video.hidden=false
      document.getElementById('video-preview').style.display = 'block';
    };
    reader.readAsDataURL(file);
  }
}
</script>

@endsection



