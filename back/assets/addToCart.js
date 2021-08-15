const { default: axios } = require("axios");
var quantity = 1;


function addQuantity(event) {
    event.preventDefault();
    quantity++;
    document.getElementById("quantity").innerHTML = quantity;
}

function removeQuantity(event) {
    event.preventDefault();
    if (quantity > 1) {
        quantity--;
        document.getElementById("quantity").innerHTML = quantity;
    }
}

function onClickBtnFav(event) {
    event.preventDefault();
    const url = this.href;
    for (let index = 0; index < quantity; index++) {
        axios.post(url);
    }
}

   document.querySelectorAll('a.addToCart').forEach(function(link){
        link.addEventListener('click', onClickBtnFav);
    })

    document.querySelectorAll('a.addQuantity').forEach(function(link){
        link.addEventListener('click', addQuantity);
    })

    document.querySelectorAll('a.removeQuantity').forEach(function(link){
        link.addEventListener('click', removeQuantity);
    })
