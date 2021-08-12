const { default: axios } = require("axios");

function onClickBtnFav(event) {
    event.preventDefault();

    const url = this.href;

    axios.post(url)
}

   document.querySelectorAll('a.addToCart').forEach(function(link){
        link.addEventListener('click', onClickBtnFav);
    })
