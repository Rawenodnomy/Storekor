@extends('layouts.admin_layout')

@section('title', 'Обновить Группу')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Обновить Группу</h1>
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
                    <form action="{{route('groups.update', $group->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputCategory">Название</label>
                                <input type="text" name="title" class="form-control" id="exampleInputCategory" value='{{$group->group_name}}'placeholder="Введите название группы" >
                                <br>
                                <label for="exampleInputCategory">На главную</label>
                                <select class="form-control" id="exampleInputCategory" name="main" id="main">
                                    @if ($group->is_main == 1)
                                    <option value="1">Да</option>
                                    <option value="0">Нет</option>
                                    @else
                                    <option value="0">Нет</option>
                                    <option value="1">Да</option>
                                    @endif
                                </select>
                                <!-- <label for="exampleInputCategory">Фото Группы</label>
                                <input type="file" name="img" class="form-control" id="exampleInputCategory" required> -->
                                <br>
                                <label for="exampleInputCategory">Описание группы</label>
                                <p class="form-control" style='height: 100%;'>{{$group->description}}</p>
                                <br>
                                <label for="exampleInputCategory">Новое описание группы</label>
                                <textarea class="form-control" name="text" id="" cols="30" rows="10"></textarea>
                                <input type="hidden" name="oldtext" id="" value='{{$group->description}}'>

                                <input type="hidden" name="id" value='{{$group->id}}'>
                                <br>
                                <label for="newphoto">
                                <img id='changephoto' src="/groups/{{$group->photo_group}}" alt="{{$group->photo_group}" style='height: 350px'>
                                </label>
                                <input id="newphoto" onchange="previewImage(event)" name='img' type="file" style='display: none;'/>
                                
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