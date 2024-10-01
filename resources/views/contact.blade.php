@include('/layouts/header')

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
                    <form action="/saveContacts" method="post">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-12">
                                <input type="text" name='FIO' id='FIO' value='{{$contact->FIO}}' class="form-control bg-light border-0 px-4 inputcontact" placeholder="ФИО получателя " style="height: 55px;">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name='city' id='city' value='{{$contact->city}}' class="form-control bg-light border-0 px-4 inputcontact" placeholder="Город" style="height: 55px;">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name='address' id='address' value='{{$contact->address}}' class="form-control bg-light border-0 px-4 inputcontact" placeholder="Адрес" style="height: 55px;">
                            </div>
                            <div class="col-sm-12">
                                <input type="number" name='index_mail' id='index_mail' value='{{$contact->index_mail}}' class="form-control bg-light border-0 px-4 inputcontact" placeholder="Индекс почты" style="height: 55px;">
                            </div>
                            <input name='user_id' type="hidden" value='{{$user_id}}'>
                            <div class="col-sm-12">
                                <button class="btn btn-primary border-inner w-100 py-3" name='id' id='btncontact' value='{{$contact->id}}' type="submit">Сохранить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@include('/layouts/links')


<script>
    const fio = document.querySelector('#FIO');
    const city = document.querySelector('#city');
    const address = document.querySelector('#address');
    const index = document.querySelector('#index_mail');
    const btncontact = document.querySelector('#btncontact');

    if (fio.value=='' || city.value=='' || address.value=='' || index.value==''){
                btncontact.disabled=true

            } else {
                btncontact.disabled=false
            }

    const inputcontact = document.querySelectorAll('.inputcontact');

    inputcontact.forEach(input => {
        input.addEventListener('input', function(){
            console.log(index.value)
            if (fio.value=='' || city.value=='' || address.value=='' || index.value==''){
                btncontact.disabled=true

            } else {
                btncontact.disabled=false
            }
        })
    })

</script>