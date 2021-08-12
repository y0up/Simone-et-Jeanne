const { default: axios } = require("axios");

function onClickBtnFav(event) {
    event.preventDefault();

    const url = this.href;
    const icone = this.querySelector('svg');

    axios.get(url).then(function (response) {

        
        if (icone.dataset.prefix == 'fas') {
            icone.dataset.prefix = 'far';
        } else {
            icone.dataset.prefix = 'fas';
        }
    });
}

   document.querySelectorAll('a.js-fav').forEach(function(link){
        link.addEventListener('click', onClickBtnFav);
    })
