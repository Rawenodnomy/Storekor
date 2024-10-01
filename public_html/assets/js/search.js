  var input = document.getElementById('sssearch'); 
  var block = document.getElementById('searchResult'); 
  
  input.oninput = function() {
        var searchValue = input.value
        $.ajax({
          url: "/getsearch",
          method: "GET",
          data: { search_value: searchValue },
          success: function(data) {
            var results = [];
            var count = [];
  
            if (input.value.length > 0){
              for (var i=0 ; i < data.length ; i++)
              {
                  if(data[i].hasOwnProperty("name") && data[i].name.toLowerCase().indexOf(searchValue)>-1 || data[i].hasOwnProperty("description") &&  data[i].description.toLowerCase().indexOf(searchValue)>-1 || data[i].hasOwnProperty("dop") &&  data[i].dop.toLowerCase().indexOf(searchValue)>-1){
                    results.push(data[i]);
                    count.push(count[i]);

                } 
              }
            }

            let countRes = ''
            const mod10 = count % 10;
            const mod100 = count % 100;
          
            if (mod10 === 1 && mod100 !== 11) {
              countRes = "Результат";
            } else if (mod10 >= 2 && mod10 <= 4 && (mod100 < 10 || mod100 >= 20)) {
              countRes = "Результата";
            } else {
              countRes = "Результатов";
            }


            results.length = 5

            if (input.value.length == 0){
              results.length = 0
            } 

            if (results.length>0){
              block.classList.add('show')
            } else {
              block.classList.remove('show')
            }

            block.innerHTML = '';
            for (var i=0 ; i < results.length ; i++){
              block.insertAdjacentHTML('beforeend', 
              `
              <a href="/showProduct/${results[i].id}" style='color: black;'>
                <div class="d-flex h-100 searchblock">
                  <hr>
                  <div class="flex-shrink-0">
                      <img class="img-fluid" src="/products/${results[i].photo}" alt="${results[i].photo}" style="width: 50px; height: 50px; object-fit: cover;">
                      <!-- <h4 class="bg-dark text-primary p-2 m-0">$99.00</h4> -->
                  </div>
                  <div class="">
                      <p class="mb-0 searchtext">${results[i].name} <br> <span style='color: gray;'>${results[i].price} ₽</span></p>
                  </div>
                </div>
              </a>
              `
              )
            }


            block.insertAdjacentHTML('beforeend', 
              `
                  <div class="d-flex h-100 searchblock">
                    <div class="">
                        <p type='submit' class="mb-0 searchtext">Всего ${count.length} ${countRes}</p>
                    </div>
                  </div>

              `
              )

          },
          error: function(error) {
            // Обработайте ошибку
          }
        });
      

  };

