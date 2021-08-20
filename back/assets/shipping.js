!function(){
	// Parameterized the widget
  $(document).ready(function widget () {  
    $("#Zone_Widget").MR_ParcelShopPicker({  
      
      
      Target: "#ParcelShopCode",
      Brand: "CC2241ID",
      Country: "FR",
      SearchDelay: '4',
      MapScrollWheel: true,
      OnParcelShopSelected:
        function (data) {
            const { default: axios } = require("axios");
            const url = document.getElementById("relayChoice").href;
            
            document.getElementById("relayName").innerHTML = data.Nom;
            document.getElementById("relayAdress1").innerHTML = data.Adresse1;
            document.getElementById("relayAdress2").innerHTML = data.Adresse2;
            document.getElementById("relayCp").innerHTML = data.CP;
            document.getElementById("relayCity").innerHTML = data.Ville;
            document.getElementById("relayCountry").innerHTML = data.Pays;
            
            axios.post(url, data).then(function (response) {
            })
        },
    }); 
  });
}();

function onClickShippingChoice(event) {
  event.preventDefault();
  const { default: axios } = require("axios");

  const link = this.id;
  const url = this.href;

  axios.get(url).then(function (response) {
});

  if (link == 'relayChoice') {

    document.getElementById('relayChoice').className = "c-is--active ";
    document.getElementById('bordeauxChoice').className = "c-is--not--active ";
    document.getElementById('relay').className = "visible";
    document.getElementById('bordeaux').className = "hidden";
  } else {
    document.getElementById('bordeauxChoice').className = "c-is--active ";
    document.getElementById('relayChoice').className = "c-is--not--active ";
    document.getElementById('relay').className = "hidden";
    document.getElementById('bordeaux').className = "visible";
  }

}

document.querySelectorAll('a.shippingChoice').forEach(function(link){
  link.addEventListener('click', onClickShippingChoice);
})


