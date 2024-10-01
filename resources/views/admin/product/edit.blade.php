@extends('layouts.admin_layout')

@section('title', 'Обновить Товар')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Обновить Товар</h1>
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
                    <form action="{{route('products.update', $product->id)}}" method="post" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputCategory">Название</label>
                                <input type="text" name="title" class="form-control" id="exampleInputCategory" value="{{$product->name}}" ><br>
                                <label for="exampleInputCategory">Цена</label>
                                <input type="number" name="price" class="form-control" id="exampleInputCategory" value="{{$product->price}}"><br>
                                <!-- <label for="exampleInputCategory">Фото товара</label> -->
                                <!-- <input type="file" name="img" class="form-control" id="exampleInputCategory" required> -->
                                <label for="exampleInputCategory">Описание товара</label>
                                <input type="hidden" name='oldtext' value="{{$product->description}}">
                                <p class="form-control" style='height: 100%; white-space: pre-wrap;'>{{$product->description}}</p><br>
                                <label for="exampleInputCategory">Новое описание товара</label>
                                <textarea name="text" id="text" class="form-control"   minlength="50"></textarea><br>
                                <label for="exampleInputCategory">Тип товара</label>
                                <br>
                                <select class="form-control" onchange="handleSelectChange(event)" id="exampleInputCategory" name="type_id" id="type_id">
                                    <option value="{{$product->type_id}}">{{$product->type}}</option>
                                    @foreach ($types as $type)
                                    <option value="{{$type->id}}">{{$type->type_products}}</option>
                                    @endforeach
                                </select>
<br>
                                <div id="groups">
                                    <label for="exampleInputCategory">Группа</label>
                                    <br>
                                    <select class="form-control" id="exampleInputCategory" name="group_id" id="group_id">
                                        <option value="{{$product->group_id}}">{{$product->group_name}}</option>
                                        @foreach ($groups as $group)
                                        <option value="{{$group->id}}">{{$group->group_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <br>

                                <label for="exampleInputCategory">Фото товара</label><br>
                                <label for="newphoto">
                                <img id='changephoto' src="/products/{{$product->photo}}" alt="{{$product->photo}}" style='height: 350px'>
                                </label>
                                <input id="newphoto" onchange="previewImage(event)" name='img' type="file" style='display: none;'/>

                                <br>


                                <div id="count_product" @if($product->type_id==3) style='display:none;' @endif>
                                    <label for="exampleInputCategory">Количество</label>
                                    
                                    <input type="number" name="count" class="form-control" id="exampleInputCategory" value="{{$product->count}}">
                                </div>
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

function handleSelectChange(event) {

var div = document.getElementById('count_product');
var groups = document.getElementById('groups');

var selectElement = event.target;

var value = selectElement.value;


if (value==10 || value==11){
    groups.style.display = 'none';
} else {
    groups.style.display = 'block';
}


if (value==3) {
    div.style.display = 'none';
} else {
    div.style.display = 'block';
}

}
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