@include('/layouts/header')


    <div class="container-fluid pt-5">
        <div class="container">

            <div class="row gx-5">
                <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 400px;">
                    <div class="position-relative h-100" >
                        <img class=" w-100 h-100" src="/products/{{$product->photo}}" style="object-fit: cover;">

                    </div>

                </div>
                <div class="col-lg-6 pt-3" >
                    

                    <h4 class="mb-4">{{$product->name}} <span style="color: gray;">@if ($stok == false) {{$product->price}} ₽ @else {{round(($product->price/100) * (100-$stok->percent))}} ₽ <span style='color:red; font-size: 80%; text-align: top;'>-{{$stok->percent}}%</span>@endif</span> </h4>

                    @if ($product->group_id !=null)
                    <h6><a href="{{action('App\Http\Controllers\MainController@getProductsByGroup', ['id' => $product->group_id])}}"> {{$product->group_name}}</a></h6>

                    @endif
                    @if (Auth::user())
                        @if ($fav == true)
                            <a  href="{{action('App\Http\Controllers\MainController@favorite', ['id' => $product->id])}}">
                                <img src="/icons/heart-o.png" alt="" style='max-width: 30px; margin-top:10px; margin-bottom:10px;'>
                            </a>
                        @else 
                            <a href="{{action('App\Http\Controllers\MainController@favorite', ['id' => $product->id])}}">
                            <img src="/icons/heart.png" alt="" style='max-width: 30px; margin-top:10px; margin-bottom:10px;'>
                            </a>
                        @endif
                    @endif
                    <p class="mb-4 prewrap">{{$product->description}}</p>
                    @if ($cosmetic != null)
                        <p><span><b>Бренд: </b></span>{{$cosmetic->brand}}</p>
                        <p><span><b>Область ухода:</b> </span>{{$cosmetic->area}}</p>
                        <p><span><b>Текстура:</b> </span>{{$cosmetic->texture}}</p>
                        <p><span><b>SPF:</b> </span>{{$cosmetic->spf}}</p>
                        <p><span><b>Формат:</b> </span>{{$cosmetic->format}}</p>
                    @endif

                    @if ($food != null)
                        <p><b>Категория: </b> {{$food->cat}}</p>
                        <p><b>Вес: </b> {{$food->weight}}</p>
                    @endif


                    @if ($versions!=[])
                        <select name="" id="vers{{$product->id}}" style='border: 0,5px black solid; padding: 3px; border-radius: 5px;'>
                            @foreach ($versions as $version)
                                <option value="{{$version->id}}">{{$version->version_name}}</option>
                            @endforeach
                        </select>
                    @endif
                    @guest
                    @elseif (auth()->user()->is_blocked !=1)
                    <br>



                    @if ($product->count>0)
                        <button class="bg-dark addBasket" data-id="{{$product->id}}" id='button_add' style="color:white; border-radius: 5px; border: 0px; padding: 10px; padding-left: 20px; padding-right: 20px; margin-top: 20px;">В корзину</button>
                    @else
                        <h4 style='color: red;'>Товар закончился</h4>
                    @endif





                    @else
                        <br>
                        <br>
                        <h4 style='color: red;'>Вы не можете заказывать товары, вы заблокированы</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid py-5">
        <div class="container">
            <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
                @if ($product->group_name !=null)
                <h1 class="display-4 text-uppercase" style="font-size: 200%;">Товары от группы {{$product->group_name}}</h1>
                @else 
                <h1 class="display-4 text-uppercase" style="font-size: 200%;">Товары в этой Категории</h1>
                @endif
            </div>
            <div class="owl-carousel testimonial-carousel">

                @foreach ($otherProducts as $other)
                    <div class="testimonial-item">
                        <a href="{{action('App\Http\Controllers\MainController@showProduct', ['id' => $other->id])}}">
                            <div class="d-flex align-items-center mb-3">
                                <img class="img-fluid flex-shrink-0" src="/products/{{$other->photo}}" style='max-height: 300px; object-fit: cover;'>
                            </div>
                            <h4>{{$other->name}} <span style="color: gray;">{{$other->price}} ₽</span></h4>
                            @if ($other->group_id !=null)
                                <p class="mb-0">{{$other->group_name}}</p>
                            @else
                                <p>{{$other->type}}</p>
                            @endif
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </div>








    @guest

    <div class="container-fluid contact position-relative px-5" style="margin-top: 90px; margin-bottom: 90px;">
        <div class="container">
            <div class="row g-5 mb-5">
                <div class="">
                    <div class="bg-primary border-inner text-center text-white p-5">
                            <h1 style="color: white">Войдите, чтобы написать комментарий</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @elseif (auth()->user()->is_blocked ==1)
        <h4 style='color: red; text-align: center;'>Вы не можете писать комментарии, вы заблокированы</h4>
    @else
    <div class="container-fluid contact position-relative px-5" style="margin-top: 90px; margin-bottom: 90px;">
        <div class="container">
            <div class="row g-5 mb-5">
                <div class="">
                    <div class="bg-primary border-inner text-center text-white p-5">
                            <h1 style="color: white">Оставить комментарий</h1>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form action="/createComment" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name='user_id' value='{{Auth::user()->id}}'>
                        
                        <div class="row g-3">
  
                            <div class="col-sm-12">
                                <textarea name="content" class="form-control bg-light border-0 px-4 py-3" name='comment' rows="4" placeholder="Комментарий к товару" id='commentForProduct'></textarea>
                            </div>
                            <label for="insphoto">
                                <!-- <div style='background-color: orange;  width: 50px;'> -->
                                    <!-- <img src="/comments/screp.png" alt="" id='' style='height: 50px; width: 50px;'> -->
                                    <i class="fas fa-image" style='color: #f93; font-size: 50px;'></i>
                                <!-- </div> -->
                            </label>

                            <input onchange="previewImage(event)" id="insphoto" name='image' type="file" style='display: none; object-fit:cover;'/>

                            <div class="col-sm-12">
                                <button name='product_id' value='{{$product->id}}' class="btn btn-primary border-inner w-100 py-3" id='commentBtn' disabled type="submit">Отправить</button>
                            </div>
                            <img src="/" alt="" id='changephoto' style=' display:none; object-fit:cover;' >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endguest

    




























    <!-- commentsBlock -->



    @if ($comments != [])
        <div class="container-fluid py-5" id='comments'>
            <div class="container">
                <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">

                    <h1 class="display-4 text-uppercase" style='font-size: 200%'>Комментарии к данному товару</h1>
                </div>
                
                @foreach ($comments as $comment)
                    <div class="testimonial-item bg-dark text-white border-inner p-4 @if($comment->position>3) commentsHidden lastest @else commentsBlock @endif" id='{{$comment->id}}'>
                        <div class="d-flex align-items-center mb-3">
                            <img class="img-fluid flex-shrink-0" src="/users/{{$comment->user_avatar}}" alt="{{$comment->user_avatar}}" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="ps-3">
                                <h4 class="text-primary text-uppercase mb-1">{{$comment->user_name}}</h4>
                                <span>{{$comment->created_at}}</span>
                            </div>
                            @guest
                            @else
                            @if (Auth::user()->id==$comment->user_id)
                            <form action="{{url('deleteComment')}}" method="post" style="position: absolute; right: 0; top:0;">
                                @csrf
                                <input name='product' type="hidden" value='{{$product->id}}' >
                                <button name='comment_id' value='{{$comment->id}}' style="background-color: red; color: white; border: 0px; padding: 10px;">Удалить</button>
                            </form>
                            @endif
                            @endif
                        </div>
                        <p class="mb-0">{{$comment->content}}</p>
                        @if ($comment->photo !=null)

                        <a href="#" class='js-open-modalimg' data-modalimg="asd_{{$comment->id}}" >
                            <img src="/comments/{{$comment->photo}}" alt="" style='height: 150px; max-width: 250px; object-fit: cover; margin-top: 10px;'>
                        </a>

                        <div class="modalimg" data-modalimg="asd_{{$comment->id}}">
                            <img src="/comments/{{$comment->photo}}" alt="" >
                        </div>


                        @endif
                    </div>
                    <br>
                @endforeach
            </div>
        </div>

        @if (count($comments)>3)
            <p style='text-align:center;'>
                <button class='showComments bg-dark' style="color:white; border-radius: 5px; border: 0px; padding: 10px; padding-left: 20px; padding-right: 20px; margin-top: 20px; max-width: 300px;">Показать все комментарии</button>
            </p>
        @endif
        <!-- Подложка под модальным окном -->
        
    @else
        <div class="container-fluid py-5">
            <div class="container">
                <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
                    <h1 class="display-4 text-uppercase" style='font-size: 200%'>Оставьте первый комментарий</h1>
                </div>
            </div>
        </div>
    @endif

    <script>
        const showComments = document.querySelector('.showComments');
        const blocks = document.querySelectorAll('.lastest');

        showComments.addEventListener('click', function(){

            let i = 0;
            while (i < blocks.length){
                blocks[i].classList.toggle('commentsBlock');
                i=i+1;
            }

            let btn = document.querySelectorAll('.commentsBlock');
            if (btn.length>3){
                showComments.innerHTML = "Скрыть комментарии";
            } else {
                showComments.innerHTML = "Показать все комментарии";
            }
            
        })
    </script>


    <div class="overlay js-overlay-modalimg"></div>

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
            const [file] = insphoto.files
            changephoto.src = URL.createObjectURL(file)
            changephoto.classList.add("changephoto");
            changephoto.setAttribute('style', 'display:block; max-height: 200px; max-width: 350px;')
        }
}
    
}

    const commentcontent = document.querySelector('#commentForProduct');
    const commentBtn = document.querySelector('#commentBtn');

    
    commentcontent.addEventListener('input', function(){
        if (commentcontent.value==''){
            commentBtn.disabled = true;
        } else {
            if (commentcontent.value.length>=10){
                commentBtn.disabled = false;
            }
            
        }
    })

    </script>

<script>
!function(e){"function"!=typeof e.matches&&(e.matches=e.msMatchesSelector||e.mozMatchesSelector||e.webkitMatchesSelector||function(e){for(var t=this,o=(t.document||t.ownerDocument).querySelectorAll(e),n=0;o[n]&&o[n]!==t;)++n;return Boolean(o[n])}),"function"!=typeof e.closest&&(e.closest=function(e){for(var t=this;t&&1===t.nodeType;){if(t.matches(e))return t;t=t.parentNode}return null})}(window.Element.prototype);


document.addEventListener('DOMContentLoaded', function() {

   /* Записываем в переменные массив элементов-кнопок и подложку.
      Подложке зададим id, чтобы не влиять на другие элементы с классом overlay*/
   var modalButtons = document.querySelectorAll('.js-open-modalimg'),
       overlay      = document.querySelector('.js-overlay-modalimg'),
       closeButtons = document.querySelectorAll('.js-modalimg-close');


   /* Перебираем массив кнопок */
   modalButtons.forEach(function(item){

      /* Назначаем каждой кнопке обработчик клика */
      item.addEventListener('click', function(e) {

         /* Предотвращаем стандартное действие элемента. Так как кнопку разные
            люди могут сделать по-разному. Кто-то сделает ссылку, кто-то кнопку.
            Нужно подстраховаться. */
         e.preventDefault();

         /* При каждом клике на кнопку мы будем забирать содержимое атрибута data-modal
            и будем искать модальное окно с таким же атрибутом. */
         var modalId = this.getAttribute('data-modalimg'),
             modalElem = document.querySelector('.modalimg[data-modalimg="' + modalId + '"]');


         /* После того как нашли нужное модальное окно, добавим классы
            подложке и окну чтобы показать их. */
         modalElem.classList.add('active');
         overlay.classList.add('active');
      }); // end click

   }); 


   closeButtons.forEach(function(item){

      item.addEventListener('click', function(e) {
         var parentModal = this.closest('.modalimg');

         parentModal.classList.remove('active');
         overlay.classList.remove('active');
      });

   }); 



    overlay.addEventListener('click', function() {
        document.querySelector('.modalimg.active').classList.remove('active');
        this.classList.remove('active');
    });




}); 
</script>

<style>
/* Стили для подложки */

.overlay {
   
   /* Скрываем подложку  */
   opacity: 0;
   visibility: hidden;
   
   position: fixed;
   top: 0;
   left: 0;
   width: 100%;
   height: 100%;
   background-color: rgba(0, 0, 0, .5);
   z-index: 20;
   transition: .3s all;
}


/* Стили для модальных окон */

.modalimg {
   
   /* Скрываем окна  */
   opacity: 0;
   visibility: hidden;
   
   
   /*  Установаем ширину окна  */
   width: 100%;
   max-width: 700px;
   
   /*  Центрируем и задаем z-index */
   position: fixed;
   top: 50%;
   left: 50%;
   transform: translate(-50%, -50%);
   z-index: 30; /* Должен быть выше чем у подложки*/
   
   /*  Побочные стили   */
   box-shadow: 0 3px 10px -.5px rgba(0, 0, 0, .2); 
   text-align: center;
   padding: 30px;
   border-radius: 3px;
   background-color: #fff;
   transition: 0.3s all;
}

.modalimg img {
    max-height: 650px; max-width: 650px; object-fit: cover;
}

@media screen and (max-width: 600px) {
    .modalimg {

   width: 100%;
   max-width: 400px;

}

.modalimg img {
    max-height: 350px; max-width: 350px; object-fit: cover;
}

}


/* Стили для активных классов подложки и окна */

.modalimg.active,
.overlay.active{
   opacity: 1;
   visibility: visible;
}




</style>

<script src="/assets/js/catalog.js" defer type="module"></script>
@include('/layouts/footer')