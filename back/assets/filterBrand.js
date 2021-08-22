function filterBrand(event) {
    event.preventDefault();

    document.querySelectorAll('div.o-card').forEach(element => {

        
        if (element.className == ('o-card' + ' ' + this.innerHTML) & (element.classList.contains("hidden") == false)) {
            element.classList.add("hidden");
            console.log(element.className);
        } else {
            element.classList.remove("hidden");
            console.log(element.className);
        }
    });
    
}

   document.querySelectorAll('a.filterBrand').forEach(function(link){
        link.addEventListener('click', filterBrand);
    })
