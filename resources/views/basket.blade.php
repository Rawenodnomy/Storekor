@include('/layouts/header')

@if(count($basket) >0)
    <div class="container-fluid bg-dark bg-img p-5 mb-5">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 text-uppercase text-white">Корзина</h1>
                <h2 class="totalPrice" style='color: white;'>Итого: </h2>
            </div>
        </div>
    </div>

    <div class="container-fluid about py-5">
        <div class="container">
            <div class="tab-class text-center">
 
                <div class="">
                    <div class="tab-pane fade show p-0 active">
                        <div class="row g-3">
                            @csrf
                            
                            @foreach($basket as $item)
                            <div id="product{{$item->position_id}}" data-vers="{{$item->basketVersion?$item->basketVersion->id:null}}">
                                <div class="d-flex h-100">
                                    <div class="flex-shrink-3">
                                        <a href="/showProduct/{{$item->basketProduct->id}}">
                                            <img class="img-fluid" src="/products/{{$item->basketProduct->photo}}" alt="{{$item->basketProduct->photo}}" style="width: 150px; height: 150px; object-fit: cover;">
                                        </a>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center text-start bg-secondary border-inner px-4" style="width: 100%;">
                                        
                                        <h5 class="text-uppercase">{{$item->basketProduct->name}} @if($item->basketVersion) ({{$item->basketVersion->version_name}}) @endif</h5>
                                        <?php $stok = DB::select('SELECT * FROM `stocks_by_types` WHERE ? between start and end AND type_id = ?', [date('Y/m/d'), $item->basketProduct->type_id]); if($stok!=[]) {$stok=$stok[0]->percent;} else{$stok=0;} ?>
                                        <h6> <span id='price' style="color: gray;">{{round(($item->basketProduct->price/100) * (100-$stok))}} </span><span style="color: gray;"> ₽</span></h6>
                                        
                                        <div class=' d-flex flex-row' >
                                        <button class="bg-dark addBasket" onclick='f()' data-vers="{{$item->basketVersion?$item->basketVersion->id:null}}" data-id="{{$item->position_id}}" id='maxx{{$item->position_id}}' data-max='{{$item->maxCount}}' data-count='{{$item->count}}' style='color: white;width: 35px;'>+</button>
                                        <h5 class="count" data-vers="{{$item->basketVersion?$item->basketVersion->id:null}}" data-count="{{$item->count}}" data-price="{{round(($item->basketProduct->price/100) * (100-$stok))}}" id="count{{$item->position_id}}" style='font-size: 200%; margin: 0px 20px 0px 20px;'>{{$item->count}}</h5>
                                        <button class="bg-dark minusBasket" data-vers="{{$item->basketVersion?$item->basketVersion->id:null}}" data-id="{{$item->position_id}}" style='color: white; border: 0px; width: 35px;'><span style='font-size: 150%; '>-</span></button>
                                        
                                        </div>
                                        <button class="removeBasket btndelete" data-vers="{{$item->basketVersion?$item->basketVersion->id:null}}" data-id="{{$item->position_id}}"><b>Удалить</b></button> 

                                        </div>



                                    </div>
                                    
                                </div>
                 
                            <br>
                            @endforeach
                            <a href="/catalog" class='bg-dark' style='color: white; padding: 20px; max-width: 400px; margin-left: auto; margin-right: auto;'>Продолжить покупки</a>
                            
                        </div>
                        

                    </div>

                </div>
            </div>
        </div>
    </div>
    




    <div class="container-fluid contact position-relative px-5" style="margin-top: 90px; padding-bottom: 90px; ">
        <div class="container">
            <div class="row g-5 mb-5">
                <div class="">
                    <div class="bg-primary border-inner text-center text-white p-5">
                            <h1 style="color: white">Данные для доставки</h1>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form action="/addorder" method="post">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-12">
                                <input type="text" name='FIO' id='FIO' value='{{$contacts->FIO}}' class="form-control bg-light border-0 px-4 inputcontact" placeholder="ФИО получателя " style="height: 55px;">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name='city' id='city' value='{{$contacts->city}}' class="form-control bg-light border-0 px-4 inputcontact" placeholder="Город" style="height: 55px;">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name='address' id='address' value='{{$contacts->address}}' class="form-control bg-light border-0 px-4 inputcontact" placeholder="Адрес" style="height: 55px;">
                            </div>
                            <div class="col-sm-12">
                                <input type="number" name='index_mail' id='index_mail' value='{{$contacts->index_mail}}' class="form-control bg-light border-0 px-4 inputcontact" placeholder="Индекс почты" style="height: 55px;">
                            </div>

                            <div class="col-sm-12">
                                <textarea class="form-control bg-light border-0 px-4 py-3" name='comment' rows="4" placeholder="Комментарий к заказу"></textarea>
                            </div>
                            <div class="col-sm-12">
                                <!-- <button  type="submit">Заказать</button> -->
                                <a href="#" id='openModal' class="btn btn-primary border-inner w-100 py-3 btncontact">Заказать</a>
                            </div>
                              <div id="modal" class="modal">
                                <div class='mt-100'>
                                    <div class="modal-content">
                                        <span class="close">&times;</span>
                                        <p style='text-align:center;'><b>Оплата</b></p>
                                        <input type="text" id="card" class="form-control bg-light border-1 px-4" placeholder="Номер карты" style="height: 55px; margin-bottom: 10px; margin-left:auto; margin-right:auto;">
                                        <input type="text" id="date" class="form-control bg-light border-1 px-4" placeholder="Дата" style="height: 55px; margin-bottom: 10px; margin-left:auto; margin-right:auto;">
                                        <input type="text" id="cvv" class="form-control bg-light border-1 px-4" placeholder="CVV" style="height: 55px; margin-bottom: 10px; margin-left:auto; margin-right:auto;">
                                        <br>   
                                        <button disabled id="btnOrder" class="btn btn-primary border-inner py-3" style='width: 50%; margin-left:auto; margin-right:auto;'>Оплатить</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br><br><br><br>
    @else
    <div style="margin-bottom: 300px;">
    <div class="container-fluid bg-dark bg-img p-5 mb-5" >
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 text-uppercase text-white" >В корзине пусто</h1>
            </div>
        </div>
    </div>
    </div>
    @endif
    <script>

    const fio = document.querySelector('#FIO');
    const city = document.querySelector('#city');
    const address = document.querySelector('#address');
    const index = document.querySelector('#index_mail');
    const btncontact = document.querySelector('.btncontact');
    if (fio.value=='' || city.value=='' || address.value=='' || index.value==''){
                btncontact.classList.add('disabled')

            } else {
                btncontact.classList.remove('disabled')
            }

    const inputcontact = document.querySelectorAll('.inputcontact');

    inputcontact.forEach(input => {
        input.addEventListener('input', function(){
            console.log(index.value)
            if (fio.value=='' || city.value=='' || address.value=='' || index.value==''){
                btncontact.classList.add('disabled')

            } else {
                btncontact.classList.remove('disabled')
            }
        })
    })

    btncontact.addEventListener("click", function(event) {
  if (this.classList.contains("disabled")) {
    event.preventDefault(); 
  }
});




    const openModalBtn = document.getElementById('openModal');
const modal = document.getElementById('modal');
const closeBtn = document.getElementsByClassName('close')[0];
openModal.addEventListener('click', (event) => {
    event.preventDefault(); 
});

openModalBtn.addEventListener('click', function() {
    modal.style.display = 'block';
});

closeBtn.addEventListener('click', function() {
    modal.style.display = 'none';
});

window.addEventListener('click', function(event) {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

const btn = document.querySelector('#btnOrder');

var card = document.querySelector('#card');

var date = document.querySelector('#date');
var cvv = document.querySelector('#cvv');

const inputs = document.querySelectorAll('input')

var regexcard = /^[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}$/gm;
var regexdate = /^(0?[1-9]|1[012])([/])([2][0-9])$/gm;
var regexcvv = /^[0-9]{3}$/gm;
var onlyNum = /^[0-9]{2}$/gm;


date.addEventListener('input', function(){
    if (date.value.match(onlyNum)){
        date.value=date.value +'/'
    }
})

inputs.forEach(input => {
input.addEventListener('input', function(){

    if (card.value.match(regexcard) && date.value.match(regexdate) && cvv.value.match(regexcvv)){
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
})})

  </script>
    <!-- <p href="/basket" style='margin: 20px;' class="btn btn-primary fs-2 back-to-top totalPriceRight"></p> -->
<style>
    .modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 60%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

</style>






























<script src="/assets/js/basket.js" defer type="module"></script>
@include('/layouts/footer')
