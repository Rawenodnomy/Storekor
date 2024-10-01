@include('/layouts/header')



<div class="container-fluid contact position-relative px-5" style="margin-top: 90px; padding-bottom: 90px; ">
        <div class="container">
            <div class="row g-5 mb-5">
                <div class="">
                    <div class="bg-primary border-inner text-center text-white p-5">
                            <h1 style="color: white">Редактирование профиля</h1>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form action="/saveEditProfile" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <label for="newphoto" class='col-sm-12' style='text-align:center;'>
                                <img id='changephoto' src="/users/{{$user->avatar}}" alt="{{$user->avatar}}" style='height: 200px; object-fit: cover;'>
                            </label>
                                <input id="newphoto" onchange="previewImage(event)" name='img' type="file" style='display: none;'/>

                            <div class="col-sm-12">
                                <input type="text" id='name' name='name' value='{{$user->name}}' class="form-control bg-light border-0 px-4" placeholder="Ваш логин" style="height: 55px;">
                            </div>
                            <div class="col-sm-12">
                                <input type="email" name='email' id='email' value='{{$user->email}}' class="form-control bg-light border-0 px-4" placeholder="Ваша почта" style="height: 55px;">
                            </div>
                            @if (session('success'))
                                <div class="col-sm-12" style='color: white;'>
                                    <p>{{session('success')}}</p>
                                </div>
                            @endif
                            <div class="col-sm-12">
                                <button class="btn btn-primary border-inner w-100 py-3" id='btn' type="submit">Обновить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script>
        const name = document.querySelector('#name');
        const email = document.querySelector('#email');
        const btn = document.querySelector('#btn');
        

        name.addEventListener('input', function(){
            if (name.value==''){
                btn.disabled = true;
            } else {
                btn.disabled = false;
            }
        })


        email.addEventListener('input', function(){
            if (email.value==''){
                btn.disabled = true;
            } else {
                btn.disabled = false;
            }
        })


    </script>



<script>

function previewImage(event) {
var input = event.target;

if (input.files && input.files[0]) {
    var file = input.files[0];
    var fileSize = file.size / 1024 / 1024; // Size in MB
    
    if (fileSize > 10) {
    alert("Файл должен весить менее 10 мб.");
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

@include('/layouts/links') 