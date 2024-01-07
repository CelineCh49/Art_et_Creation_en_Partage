
// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

/**Lightbox */
var images = document.querySelectorAll('.image-container img');
var modal = document.querySelector('.popup-image');
var closeButton = document.querySelector('.popup-image span');

images.forEach(function(image) {
  image.addEventListener('click', function() {
   
    modal.style.display = 'block';

    var imageSrc = image.getAttribute('src');
    var modalImage = modal.querySelector('img');
    modalImage.setAttribute('src', imageSrc);
  });
});

closeButton.addEventListener('click', function() {
  modal.style.display = 'none';
});


/**Burger */
var navig = document.getElementById('navig');
var burgerButton = document.getElementById('burgerButton');

burgerButton.addEventListener('click', function(){
  navig.style.display='block';
});