const activeImage = document.querySelector(".o-product__image .active");
        const productImages = document.querySelectorAll(".o-image__list img");

function changeImage(e) {
  activeImage.src = e.target.src;
}


productImages.forEach(image => image.addEventListener("click", changeImage));