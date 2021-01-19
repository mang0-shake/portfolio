  function add(id, price, page)
  {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", "/catalog?addGood=" + id, true ); // false for synchronous request
    xmlHttp.send( null );
    if(!page){
        convertToCart(id);
    }
    // return xmlHttp.responseText;
    if(page){
    let element = document.querySelector("#count-of-item-" + id );
    let count = +element.innerHTML;
    count++;
    element.innerHTML = count;
    document.querySelector("#product-count-" + id ).innerHTML++;
    document.querySelector("#basket-total-product-price-" + id).innerHTML = count * price;
    getTotal();
  }
  }
  function remove(id, price)
  {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", "/catalog?removeGood=" + id, true ); // false for synchronous request
    xmlHttp.send( null );
    let count = document.querySelector("#count-of-item-" + id ).innerHTML;
        if(count == 1 && document.querySelectorAll(".basket-wrp").length == 1){
            document.querySelector(".basket-amount").remove();
            document.querySelector(".order-button-wrp").remove();
            document.querySelector("#basket-item-" + id).remove();
            var basket = document.querySelector(".basket");
            var new_p = document.createElement('p');
            new_p.innerHTML = "Корзина пуста";
            basket.insertAdjacentElement('beforeend', new_p);
        } 
        else if (count == 1){
        document.querySelector("#basket-item-" + id).remove();
        } 
        else {
        count--;
        document.querySelector("#count-of-item-" + id ).innerHTML = count;
        document.querySelector("#product-count-" + id ).innerHTML--;
        document.querySelector("#basket-total-product-price-" + id).innerHTML = count * price;
    }
    // return xmlHttp.responseText;
    getTotal();
  }
  function getTotal(){
  let total = 0;
    let sums = document.querySelectorAll(".basket-total-product-price");
    for(let i=0; i<sums.length; i++){
      total+=+sums[i].innerHTML;
    }
    document.querySelector('.basket-total-price').innerHTML = total;
  }
    
    
    function convertToCart(id){
  let announcement = document.createElement("div");
    announcement.setAttribute("class", "announcement");
    //
    let announcement_text = document.createElement("p");
    announcement_text.setAttribute("class", "announcement-text");
    announcement_text.innerHTML = "Successfully added";
    announcement.appendChild(announcement_text);
    document.querySelector(".announcement-wrp").appendChild(announcement);
    
        setTimeout(function(){
      announcement.remove();
    }, 3999);
    
    announcement.animate({opacity: '0'}, 4000);
    
  }