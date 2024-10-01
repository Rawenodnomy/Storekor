const count = document.getElementById('countbasket'); 

console.log(count)





input.oninput = function() {
    $.ajax({
      url: "/countBasket",
      method: "GET",
      data: { search_value: searchValue },
      success: function(data) {
        console.log(data)


      },
      error: function(error) {
        // Обработайте ошибку
      }
    });
  

};