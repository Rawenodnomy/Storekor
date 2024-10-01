@extends('layouts.admin_layout')

@section('title', 'Обновить Новость')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Обновить Новость</h1>
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
                    <form action="{{route('news.update', $new->id)}}" method="post" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputCategory">Заголовок</label>
                                <input type="text" name="title" class="form-control" id="exampleInputCategory" value='{{$new->title}}' placeholder="Введите название товара" >
                                <br>


                                <label for="exampleInputCategory">Текст</label>
                                <br>
                                <!-- <input type="text" name="text" class="form-control" style='height: 200px;' id="exampleInputCategory" value=''> -->
                                <textarea name="newtext" onclick="textAreaAdjust(this)" id="" class='form-control'></textarea>
                                <input type="hidden" name="oldtext" id="oldtext" value="{{$new->content}}">
                                <br>



                                


                                <label for="exampleInputCategory">Фото товара</label><br>
                                <label for="newphoto">
                                <img id='changephoto' src="/news/{{$new->image}}" alt="{{$new->image}}" style='height: 350px'>
                                </label>
                                <input id="newphoto" onchange="previewImage(event)" name='img' type="file" style='display: none;'/>

                                <br>
                                <input type="hidden" name="oldphoto" value='{{$new->image}}'>
                                <br>

                                
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
  const textarea = document.querySelector('textarea');
  const oldtext = document.querySelector('#oldtext').value;

  textarea.value = oldtext;
  

  function textAreaAdjust(element) {
  element.style.height = "1px";
  element.style.height = (25+element.scrollHeight)+"px";
    }
</script>



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
