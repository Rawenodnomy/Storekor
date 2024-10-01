@extends('layouts.admin_layout')

@section('title', 'Обновить Товар')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Обновить Косметику</h1>
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
                    <form action="{{route('cosmetics.update', $product->id)}}" method="post" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputCategory">Название</label>
                                <input type="text" name="title" class="form-control" id="exampleInputCategory" value='{{$product->name}}' placeholder="Введите название товара" >
                                <br>
                                <label for="exampleInputCategory">Цена</label>
                                <input type="number" name="price" class="form-control" value='{{$product->price}}' id="exampleInputCategory">
                                <br>

                                <label for="exampleInputCategory">Описание товара</label>
                                <input type="hidden" name='oldtext' value="{{$product->description}}">
                                <p class="form-control" style='height: 100%'>{{$product->description}}</p>
                                <br>
                               
                                <label for="exampleInputCategory">Новое описание товара</label>
                                <textarea name="text" id="text" class="form-control"  minlength="50"></textarea>
                                <br>
                                <label for="exampleInputCategory">Бренд</label>
                                <br>
                                <select class="form-control" id="exampleInputCategory" name="brand" id="asd">
                                    @foreach ($brands as $brand)
                                        @if ($brand->id == $product->brand_id)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endif
                                    @endforeach
                                    @foreach ($brands as $brand)
                                        @if ($brand->id != $product->brand_id)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endif
                                    @endforeach
                                </select>



                                <br>
                                <label for="exampleInputCategory">Формат</label>
                                <br>
                                <select class="form-control" id="exampleInputCategory" name="format" id="asd">
                                    @foreach ($formats as $format)
                                        @if ($format->id == $product->format_id)
                                            <option value="{{$format->id}}">{{$format->format}}</option>
                                        @endif
                                    @endforeach

                                    @foreach ($formats as $format)
                                        @if ($format->id != $product->format_id)
                                            <option value="{{$format->id}}">{{$format->format}}</option>
                                        @endif
                                    @endforeach
                                </select>

                                <br>
                                <label for="exampleInputCategory">SPF</label>
                                <br>
                                <select class="form-control" id="exampleInputCategory" name="spf" id="asd">
                                    @foreach ($spfs as $spf)
                                        @if ($spf->id == $product->spf_id)
                                            <option value="{{$spf->id}}">{{$spf->spf}}</option>
                                        @endif
                                    @endforeach
                                    @foreach ($spfs as $spf)
                                        @if ($spf->id != $product->spf_id)
                                            <option value="{{$spf->id}}">{{$spf->spf}}</option>
                                        @endif
                                    @endforeach
                                </select>



                                <br>
                                <label for="exampleInputCategory">Текстура</label>
                                <br>
                                <select class="form-control" id="exampleInputCategory" name="texture" id="asd">
                                    @foreach ($textures as $texture)
                                        @if ($texture->id == $product->texture_id)
                                            <option value="{{$texture->id}}">{{$texture->texture}}</option>
                                        @endif
                                    @endforeach
                                    @foreach ($textures as $texture)
                                        @if ($texture->id != $product->texture_id)
                                            <option value="{{$texture->id}}">{{$texture->texture}}</option>
                                        @endif
                                    @endforeach
                                </select>

                                


                                <br>
                                <label for="exampleInputCategory">Область применения</label>
                                <br>
                                <select class="form-control" id="exampleInputCategory" name="area" id="group_id">
                                    @foreach ($areas as $area)
                                        @if ($area->id == $product->area_id)
                                            <option value="{{$area->id}}">{{$area->zone}}</option>
                                        @endif
                                    @endforeach
                                    @foreach ($areas as $area)
                                        @if ($area->id != $product->area_id)
                                            <option value="{{$area->id}}">{{$area->zone}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <br>




                                <label for="exampleInputCategory">Объем</label>
                                <br>
                                <input type="number" name="volume" class="form-control" id="exampleInputCategory" value='{{$product->volume}}'>
                                <br>





                                



                                <label for="exampleInputCategory">Фото товара</label><br>
                                <label for="newphoto">
                                <img id='changephoto' src="/products/{{$product->photo}}" alt="{{$product->photo}}" style='height: 350px'>
                                </label>
                                <input id="newphoto" onchange="previewImage(event)" name='img' type="file" style='display: none;'/>

                                <br>
                                <input type="hidden" name="oldphoto" value='{{$product->photo}}'>
                                <br>

                                <label for="exampleInputCategory">Количество</label>
                                <input type="number" name="count" class="form-control" id="exampleInputCategory" value='{{$product->count}}'>

                                
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