@extends('layouts.admin_layout')

@section('title', 'Добавить Акцию')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Добавить Акцию</h1>
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
                    <form action="{{route('stocks.store')}}" method="post" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputCategory">Заголовок акции</label>
                                <input type="text" name="title" class="form-control" id="exampleInputCategory" placeholder="Введите заголовок акции" >
                                <br>

                                <label for="exampleInputCategory">Категория акции</label>
                                <select name="type" id="" class="form-control">
                                    @foreach ($types as $type)
                                        <option value="{{$type->id}}">{{$type->type_products}}</option>
                                    @endforeach
                                </select>
                                <br>
                                <label for="exampleInputCategory">Процент скидки</label>
                                <input type="number" name="percent" class="form-control" id="exampleInputCategory">
                                <br>
                                <label for="exampleInputCategory">Дата начала акции</label>
                                <input type="date" name="start" min='{{$date}}' class="form-control" id="start-date">
                                <br>
                                <label for="exampleInputCategory">Дата окончания акции</label>
                                <input type="date" name="end" min='{{$min}}' class="form-control" id="end-date" >

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


<script>
  const startDateInput = document.getElementById('start-date');
  const endDateInput = document.getElementById('end-date');

  startDateInput.addEventListener('change', () => {
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    if (endDate <= startDate) {
      endDateInput.value = new Date(startDate.getTime() + 86400000).toISOString().substring(0, 10);
    }
  });




  endDateInput.addEventListener('change', () => {
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    if (endDate <= startDate) {
      endDateInput.value = new Date(startDate.getTime() + 86400000).toISOString().substring(0, 10);
    }
  });

</script>




@endsection

