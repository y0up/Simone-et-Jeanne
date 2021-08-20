const { default: axios } = require("axios");
var quantity = 1;
var price = document.getElementById("price").innerHTML;
const productPrice = document.getElementById("productPrice").innerHTML;


function addQuantity(event) {
    event.preventDefault();
    quantity++;
    document.getElementById("quantity").innerHTML = quantity;
    price = price + productPrice;
}

function removeQuantity(event) {
    event.preventDefault();
    if (quantity > 1) {
        quantity--;
        document.getElementById("quantity").innerHTML = quantity;
    }
}


    document.querySelectorAll('a.addQuantity').forEach(function(link){
        link.addEventListener('click', addQuantity);
    })

    document.querySelectorAll('a.removeQuantity').forEach(function(link){
        link.addEventListener('click', removeQuantity);
    })


