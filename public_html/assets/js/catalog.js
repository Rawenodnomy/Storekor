import {feachGET , feachPOST} from "./featch.js";

let csrf = document.querySelector('input[name="_token"]').value;
document.querySelectorAll('.addBasket').forEach(item =>{
    item.addEventListener('click', function(e){
        
        let versSelect = document.querySelector(`#vers${e.currentTarget.dataset.id}`)
        let data = {
            vers: null,
            _token: csrf
        };
        if(versSelect){
            data.vers = versSelect.value;
        }
        feachPOST(`/basketadd/${e.currentTarget.dataset.id}`, data)
    });
})



let orders = {price: {number:1, value: 0}, hovizn: {number:2, value:0}};
let type = document.querySelector('#type').value;
let group = document.querySelector('#group').value;



// let csrf = document.querySelector('input[name="_token"]').value;
// console.log(csrf)

function getAsyncCatalog(){
    let data = {
        orders: orders,
        type:type,
        group:group,
        _token: csrf
    }
    // console.log(111)
    feachPOST('/getcatalog', data).then(res =>{
        return res
    }).then(data =>{
        // console.log(data)
        

        if(data){
            let auth = document.querySelector('#user_id').value;
            let blocked = document.querySelector('#is_blocked').value;
            let block = document.querySelector('.cards-block')
            let bblock = document.querySelector('.bblock');
            

            const more = document.querySelector('.more');
            more.classList.add('ordersHidden')

            block.innerHTML='';
            if(group && type){
                bblock.innerHTML=`<h2>Товары группы: ${data[0].product_group.group_name} в категории ${data[0].product_category.type_products}</h2>`;
            }
            else if(type){
                bblock.innerHTML=`<h2>Товары в категории: ${data[0].product_category.type_products}</h2>`;
            }
            else if(group){
                bblock.innerHTML=`<h2>Товары группы: ${data[0].product_group.group_name}</h2>`;
            }

            let procAlb = document.querySelector('.stock_3').value;
            let procCards = document.querySelector('.stock_4').value;

            
            data.forEach(item =>{


                if (item.type_id == 3 && procAlb!='none'){
                    item.price = Math.ceil((item.price/100) * (100-procAlb))
                }


                if (item.type_id == 4 && procCards!='none'){
                    item.price = Math.ceil((item.price/100) * (100-procCards))
                }


                if (item.type_id == 3 && procAlb!='none'){
                    item.procent = `<span style='color: red; font-size: 60%;'>-${procAlb}%</span>`
                } else if (item.type_id == 4 && procCards!='none') {
                    item.procent = `<span style='color: red; font-size: 60%;'>-${procCards}%</span>`
                }else {
                    item.procent = `<span></span>`
                }


                if (item.type_id == 4){
                    item.dop = `<div style='padding-top: 32px;'></div>`
                } else {
                    item.dop =''
                }

                


                let totalCount = 0;
                let selectStr = '';
                if(item.product_albums){
                    // console.log(item.product_albums.album_version)
                    
                    item.product_albums.album_version.forEach(item=>{
                        totalCount+=item.count
                    })
                    // console.log(totalCount)

  
                        if(item.product_albums.album_version){
                            selectStr +=  `                    
                            <div class="versionSelecter">
                            <select name="" id="vers${item.id}" style='border: 0,5px black solid; padding: 3px; border-radius: 5px;'>
                            `
                            
                            item.product_albums.album_version.forEach(vers => {

                                if(vers.count>0){
                                    selectStr += `<option value="${vers.id}">${vers.version_name}</option>`
                                }
                                
                            })
                            selectStr +=  `</select></div>`
                        }


                } else{

                    totalCount=item.count

                }


                if(totalCount>=1){
                if(auth && blocked!=1){
                    block.insertAdjacentHTML('beforeend',
                    `
                    <div class="col-lg-4 col-md-6">
                        <div class="team-item">
                            <a href="/showProduct/${item.id}">
                                <div class="position-relative overflow-hidden">
                                    <img class="img-fluid w-100" src="/products/${item.photo}" alt="" style="height: 350px; object-fit: cover;">
                                </div>
                            </a>
                            <h4 class="text-uppercase" style="margin-top: 15px;">${item.name} <span style="color:gray;">${item.price} ₽</span> ${item.procent} </h4>
                            <h6>${item.product_group.group_name}</h6>
                            <div class='row'>
                            `
                        
                    +selectStr+
                    `
                    ${item.dop}
                    <button class="addBasket bg-dark" data-id="${item.id}" style="color:white; border-radius: 5px; margin-left: 10px; border: 0px; padding: 10px; padding-left: 20px; padding-right: 20px; margin-top: 20px;">в корзину</button>
                        </div>
                        </div>
                    </div>
                    `
                    )
                } else if (auth && blocked==1){
                    block.insertAdjacentHTML('beforeend',
                        `
                        <div class="col-lg-4 col-md-6">
                            <div class="team-item">
                                <a href="/showProduct/${item.id}">
                                    <div class="position-relative overflow-hidden">
                                        <img class="img-fluid w-100" src="/products/${item.photo}" alt="" style="height: 350px; object-fit: cover;">
                                    </div>
                                </a>
                                <h4 class="text-uppercase" style="margin-top: 15px;">${item.name} <span style="color:gray;">${item.price} ₽</span> ${item.procent} </h4>
                                <h6>${item.product_group.group_name}</h6>
                                <div class='row'>
                                `
                            
                        +selectStr+
                        `
                        ${item.dop}
                            <br>
                            <h6 style='color:red;'>Вы заблокированы</h6>
                        `
                        )
                }
                else{
                    block.insertAdjacentHTML('beforeend',
                    `
                    <div class="col-lg-4 col-md-6">
                        <div class="team-item">
                            <a href="/showProduct/${item.id}">
                                <div class="position-relative overflow-hidden">
                                    <img class="img-fluid w-100" src="/products/${item.photo}" alt="" style="height: 350px; object-fit: cover;">
                                </div>
                            </a>
                            <h4 class="text-uppercase" style="margin-top: 15px;">${item.name} <span style="color:gray;">${item.price} ₽</span> ${item.procent} </h4>
                            <h6>${item.product_group.group_name}</h6>
                            <div class='row'>

                            `
                            +selectStr+
                            `
                            </div>
                        </div>
                    </div>
                    `
                    )
                }
                }

            })
            document.querySelectorAll('.addBasket').forEach(item =>{
                item.addEventListener('click', function(e){
                    // console.log(111111)
                    let versSelect = document.querySelector(`#vers${+e.currentTarget.dataset.id}` )
                    let data = {
                        vers: null,
                        _token: csrf
                    };
                    if(versSelect){
                        data.vers = versSelect.value;
                    }
                    feachPOST(`/basketadd/${e.currentTarget.dataset.id}`, data)
                });
            })
        }
    })
}

document.querySelector('#type').addEventListener('change', function(e){
    type = e.currentTarget.value;
    getAsyncCatalog()
})

document.querySelector('#group').addEventListener('change', function(e){
    group = e.currentTarget.value;
    getAsyncCatalog()
})
document.querySelector('#priceOrder').addEventListener('click', function(e){
    orders.price.number = 1;
    orders.hovizn.number = 2;
    if(orders.price.value == 0){
        orders.price.value = 1
        e.currentTarget.textContent = 'Цена ↑'
    }
    else if(orders.price.value == 1){
        orders.price.value = -1
        e.currentTarget.textContent = 'Цена ↓'
    }
    else if(orders.price.value == -1){
        orders.price.value = 0
        e.currentTarget.textContent = 'Цена'
    }
    getAsyncCatalog()
})
document.querySelector('#hoviznOrder').addEventListener('click', function(e){
    orders.price.number = 2;
    orders.hovizn.number =1;
    if(orders.hovizn.value == 0){
        orders.hovizn.value = 1
        e.currentTarget.textContent = 'Новизна ↑'
    }
    else if(orders.hovizn.value == 1){
        orders.hovizn.value = -1
        e.currentTarget.textContent = 'Новизна ↓'
    }
    else if(orders.hovizn.value == -1){
        orders.hovizn.value = 0
        e.currentTarget.textContent = 'Новизна'
    }
    getAsyncCatalog()
})

