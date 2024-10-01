import {feachGET, feachPOST} from "./featch.js";
let csrf = document.querySelector('input[name="_token"]').value;
let totalPrice = 0;
let basket = document.querySelector('.basket')

document.querySelectorAll('.count').forEach(item=>{
    totalPrice += +item.dataset.count * +item.dataset.price
})
document.querySelector('.totalPrice').textContent = `Итого: ${totalPrice} ₽`;
document.querySelector('.totalPriceRight').textContent = `Итого: ${totalPrice} ₽`;






document.querySelectorAll('.addBasket').forEach(item =>{
  
    item.addEventListener('click', function(e){
        // console.log(item);
        let datastart = {
            vers: e.currentTarget.dataset.vers,
            id: e.currentTarget.dataset.id,
            max: e.currentTarget.dataset.max,
            count: e.currentTarget.dataset.count,
   
            _token: csrf
        };
        datastart.count = Number(datastart.count)
        datastart.max = Number(datastart.max)


        console.log(datastart.count)
        console.log(datastart.max)

        if (datastart.count<datastart.max){
        feachPOST(`/basketadd/${e.currentTarget.dataset.id}`, datastart).then(res =>{
            return res
        }).then(data =>{
                document.querySelector(`#maxx${data.position}`).dataset.count = data.count;
                document.querySelector(`#count${data.position}[data-vers="${datastart.vers}"]`).dataset.count = data.count;
                document.querySelector(`#count${data.position}[data-vers="${datastart.vers}"]`).textContent = data.count
                totalPrice = 0;
                document.querySelectorAll('.count').forEach(item=>{
                    totalPrice += +item.dataset.count * +item.dataset.price
                })
                document.querySelector('.totalPrice').textContent = `Итого: ${totalPrice} ₽`;
                document.querySelector('.totalPriceRight').textContent = `Итого: ${totalPrice} ₽`;
        })
    }
    });
})





document.querySelectorAll('.minusBasket').forEach(item =>{
    item.addEventListener('click', function(e){
        let datastart = {
            vers: e.currentTarget.dataset.vers,
            count: e.currentTarget.dataset.count,
            _token: csrf
        };
        feachPOST(`/basketminus/${e.currentTarget.dataset.id}`, datastart).then(res =>{
            return res;
        }).then(data =>{

            if(data.deleted){
                document.querySelector(`#product${data.position}`).remove();
                totalPrice = 0;
                document.querySelectorAll('.count').forEach(item=>{
                    totalPrice += +item.dataset.count * +item.dataset.price
                })
                document.querySelector('.totalPrice').textContent = `Итого: ${totalPrice} ₽`;
                document.querySelector('.totalPriceRight').textContent = `Итого: ${totalPrice} ₽`;
            }
            else{
                document.querySelector(`#maxx${data.position}`).dataset.count = data.count;
                document.querySelector(`#count${data.position}[data-vers="${datastart.vers}"]`).dataset.count = data.count;
                document.querySelector(`#count${data.position}[data-vers="${datastart.vers}"]`).textContent = data.count
                totalPrice = 0;
                document.querySelectorAll('.count').forEach(item=>{
                    totalPrice += +item.dataset.count * +item.dataset.price
                })
                document.querySelector('.totalPrice').textContent = `Итого: ${totalPrice} ₽`;
                document.querySelector('.totalPriceRight').textContent = `Итого: ${totalPrice} ₽`;
            }
            if(document.querySelectorAll('.count').length == 0){
                basket.innerHTML = '    <div><h2>В корзине пусто</h2></div>'
            }

        })
    });
})
document.querySelectorAll('.removeBasket').forEach(item =>{
    item.addEventListener('click', function(e){
        let datastart = {
            vers: e.currentTarget.dataset.vers,
            _token: csrf
        };
        feachPOST(`/basketremove/${e.currentTarget.dataset.id}`,datastart).then(res =>{
            return res;
        }).then(data =>{
            if(data.deleted){
                document.querySelector(`#product${data.position}[data-vers="${datastart.vers}"]`).remove();
            }
            totalPrice = 0;
            document.querySelectorAll('.count').forEach(item=>{
                totalPrice += +item.dataset.count * +item.dataset.price
            })
            document.querySelector('.totalPrice').textContent = `Итого: ${totalPrice}р`;
            document.querySelector('.totalPriceRight').textContent = `Итого: ${totalPrice} ₽`;
            if(document.querySelectorAll('.count').length == 0){
                basket.innerHTML = '    <div><h2>В корзине пусто</h2></div>'
            }
        })
    });
})
