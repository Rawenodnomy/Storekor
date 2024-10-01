
<?php 
    use Illuminate\Support\Facades\DB;
    $infos = DB::select('SELECT * FROM information');
?>
<div class="container-fluid  text-secondary" style="margin-top: 90px; background-color: black;">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-4 col-md-6 mb-lg-n5">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary border-inner p-4">
                        <a href="/" class="navbar-brand">
                            <h1 class="m-0 text-uppercase text-white">STOREKOR</h1>
                        </a>
                    </div>
                </div>
                <div class="col-lg-19 col-md-6">
                    <div class="row gx-5">

                        <div class="col-lg-4 col-md-12 pt-0 pt-5 pt-lg-5 mb-5">
                            <h4 class="text-primary text-uppercase mb-4">Каталог</h4>
                            <div class="d-flex flex-column justify-content-start">
                                <a class="text-secondary mb-2" href="/catalog"><i class="bi text-primary me-2">•</i>K-pop</a>
                                <a class="text-secondary mb-2" href="/catalogCosmetc"><i class="bi  text-primary me-2">•</i>Косметика</a>
                                <a class="text-secondary mb-2" href="/catalogFood"><i class="bi  text-primary me-2">•</i>Питание</a>

                            </div>
                        </div>


                        <div class="col-lg-4 col-md-12 pt-0 pt-5 pt-lg-5 mb-5">
                            <h4 class="text-primary text-uppercase mb-4">Полезная Информация</h4>
                            <div class="d-flex flex-column justify-content-start">
                                @foreach ($infos as $info)
                                    <a class="text-secondary mb-2" href="/getInfo/{{$info->id}}"><i class="bi text-primary me-2">•</i>{{$info->heading}}</a>
                                @endforeach
                            </div>
                        </div>


                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <h4 class="text-primary text-uppercase mb-4">Наши Контакты</h4>
                            <div class="d-flex mb-2">
                                <i><img src="/icons/loc.png" alt="" style='max-width: 20px; margin:3px;'></i>
                                <p class="mb-0">Город Екатеринбург</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i><img src="/icons/mail.png" alt="" style='max-width: 20px; margin:3px;'></i>
                                <p class="mb-0">storekor@gmail.com</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i><img src="/icons/phone.png" alt="" style='max-width: 20px; margin:3px;'></i>
                                <p class="mb-0">+7 (908) 064-46-70</p>
                            </div>
                            <div class="d-flex mt-4">
                                <a class="btn btn-lg btn-primary btn-lg-square rounded-0 me-2" href="https://vk.com/storekor"><svg xmlns="http://www.w3.org/2000/svg" width="30" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906q-1.168.486-4.666 2.01-.567.225-.595.442c-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294q.39.01.868-.32 3.269-2.206 3.374-2.23c.05-.012.12-.026.166.016s.042.12.037.141c-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8 8 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629q.14.092.27.187c.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.4 1.4 0 0 0-.013-.315.34.34 0 0 0-.114-.217.53.53 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09"/></svg></a>
                                <a class="btn btn-lg btn-primary btn-lg-square rounded-0 me-2" href="https://vk.com/storekor"><svg style='backgound-color: white;' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="40" zoomAndPan="magnify" viewBox="0 0 30 30.000001" preserveAspectRatio="xMidYMid meet" version="1.0" id="IconChangeColor"><defs><clipPath id="id1"><path d="M 2.371094 2.394531 L 26 2.394531 L 26 26 L 2.371094 26 Z M 2.371094 2.394531 " clip-rule="nonzero" id="mainIconPathAttribute" fill="currentColor"></path></clipPath></defs><g clip-path="url(#id1)"><path fill="currentColor" d="M 13.496094 2.597656 C 10.730469 2.804688 8.210938 3.941406 6.230469 5.875 C 3.625 8.414062 2.375 12.011719 2.839844 15.625 C 3.414062 20.140625 6.601562 23.902344 10.976562 25.234375 C 14.359375 26.265625 18.125 25.652344 20.992188 23.613281 C 24.515625 21.101562 26.347656 16.9375 25.804688 12.675781 C 25.230469 8.15625 22.042969 4.394531 17.667969 3.0625 C 16.304688 2.660156 14.914062 2.503906 13.496094 2.601562 Z M 14.378906 9.773438 C 14.96875 9.832031 15.265625 9.925781 15.398438 10.097656 C 15.433594 10.144531 15.488281 10.261719 15.523438 10.359375 C 15.589844 10.5625 15.59375 10.792969 15.550781 12.527344 C 15.523438 13.609375 15.539062 13.945312 15.628906 14.183594 C 15.726562 14.460938 15.960938 14.585938 16.164062 14.480469 C 16.347656 14.382812 16.777344 13.914062 17.058594 13.492188 C 17.707031 12.53125 18.101562 11.789062 18.574219 10.640625 C 18.671875 10.414062 18.800781 10.261719 18.933594 10.226562 C 18.984375 10.210938 19.742188 10.199219 20.667969 10.195312 L 22.308594 10.191406 L 22.449219 10.25 C 22.632812 10.304688 22.714844 10.429688 22.695312 10.621094 C 22.695312 10.980469 22.320312 11.722656 21.675781 12.625 C 21.585938 12.75 21.253906 13.195312 20.933594 13.613281 C 20.230469 14.535156 20.078125 14.75 19.972656 14.976562 C 19.835938 15.257812 19.871094 15.492188 20.082031 15.765625 C 20.140625 15.84375 20.453125 16.15625 20.769531 16.460938 C 21.65625 17.3125 22.058594 17.757812 22.386719 18.246094 C 22.621094 18.601562 22.714844 18.859375 22.675781 19.085938 C 22.65625 19.207031 22.535156 19.355469 22.394531 19.425781 C 22.230469 19.511719 21.976562 19.527344 20.582031 19.546875 L 19.261719 19.5625 L 19.046875 19.492188 C 18.503906 19.3125 18.140625 19.007812 17.316406 18.054688 C 16.859375 17.527344 16.519531 17.265625 16.285156 17.265625 C 16.070312 17.265625 15.789062 17.550781 15.679688 17.910156 C 15.597656 18.148438 15.570312 18.335938 15.542969 18.75 C 15.515625 19.246094 15.457031 19.355469 15.15625 19.480469 C 15.046875 19.53125 13.683594 19.546875 13.324219 19.507812 C 12.601562 19.429688 11.933594 19.199219 11.269531 18.8125 C 10.304688 18.25 9.714844 17.742188 9.082031 16.9375 C 7.984375 15.53125 7.234375 14.335938 6.324219 12.535156 C 5.976562 11.832031 5.558594 10.925781 5.492188 10.707031 C 5.425781 10.484375 5.519531 10.300781 5.738281 10.230469 C 5.8125 10.207031 6.234375 10.195312 7.191406 10.179688 L 8.539062 10.167969 L 8.695312 10.226562 C 8.941406 10.320312 9.042969 10.445312 9.214844 10.851562 C 9.359375 11.195312 10.136719 12.753906 10.378906 13.191406 C 10.628906 13.628906 10.890625 14 11.121094 14.214844 C 11.402344 14.492188 11.527344 14.558594 11.726562 14.542969 C 11.894531 14.527344 11.9375 14.488281 12.042969 14.261719 C 12.296875 13.714844 12.335938 11.867188 12.109375 11.140625 C 11.980469 10.726562 11.785156 10.570312 11.246094 10.453125 C 11.152344 10.433594 11.152344 10.398438 11.238281 10.273438 C 11.445312 9.976562 11.816406 9.832031 12.523438 9.773438 C 12.910156 9.742188 14.058594 9.738281 14.375 9.773438 Z M 14.378906 9.773438 " fill-opacity="1" fill-rule="nonzero" id="mainIconPathAttribute"></path></g></svg></a>
                                <a class="btn btn-lg btn-primary btn-lg-square rounded-0 me-2" href="https://vk.com/storekor"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" id="IconChangeColor" width="20"><!--! Font Awesome Free 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. --><path d="M184.2 177.1c0-22.1 17.9-40 39.8-40s39.8 17.9 39.8 40c0 22-17.9 39.8-39.8 39.8s-39.8-17.9-39.8-39.8zM448 80v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-305.1 97.1c0 44.6 36.4 80.9 81.1 80.9s81.1-36.2 81.1-80.9c0-44.8-36.4-81.1-81.1-81.1s-81.1 36.2-81.1 81.1zm174.5 90.7c-4.6-9.1-17.3-16.8-34.1-3.6 0 0-22.7 18-59.3 18s-59.3-18-59.3-18c-16.8-13.2-29.5-5.5-34.1 3.6-7.9 16.1 1.1 23.7 21.4 37 17.3 11.1 41.2 15.2 56.6 16.8l-12.9 12.9c-18.2 18-35.5 35.5-47.7 47.7-17.6 17.6 10.7 45.8 28.4 28.6l47.7-47.9c18.2 18.2 35.7 35.7 47.7 47.9 17.6 17.2 46-10.7 28.6-28.6l-47.7-47.7-13-12.9c15.5-1.6 39.1-5.9 56.2-16.8 20.4-13.3 29.3-21 21.5-37z" id="mainIconPathAttribute" fill="currentColor"></path></svg></a>
                                
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    
    @if(Auth::user() && auth()->user()->is_blocked!=1)
    <a href="/basket" style='color:white;'>

    
    <div class="btn basketa btn-primary fs-2 back-to-top totalPriceRight" style='margin: 20px;'>
    
        <i class="fa fa-shopping-basket"></i>
        <!-- <span class='countbasket' id='countbasket'>3</span> -->
    </div>
    </a>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    @endif

    <script>
        let addProd = document.querySelectorAll('.addBasket')
        const basketa = document.querySelector('.basketa')
        
        addProd.forEach(item => {
            item.addEventListener('click', () => {
                basketa.classList.add("added");
            setTimeout(function() {
                basketa.classList.remove("added");
            }, 1000);
            })})

    </script>


  


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="/lib/bsjs/script.js"></script>
    <script src="/lib/easing/easing.min.js"></script>
    <script src="/lib/waypoints/waypoints.min.js"></script>
    <script src="/lib/counterup/counterup.min.js"></script>
    <script src="/lib/owlcarousel/owl.carousel.min.js"></script>


    <script src="/assets/js/main.js"></script>

</body>

</html>

