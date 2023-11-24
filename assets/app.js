
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





/**burger menu */
// icons.addEventListener('click', ()=>{
//   nav.classList.toggle("navactive");
// })

// var icons = document.getElementById("icons");
// var nav = document.getElementById("nav");

// icons.addEventListener('click', function () {
//   nav.classList.toggle('navactive');
// });

// let toggle = document.querySelector('.toggle');
// let nav = document.querySelector('nav');

// toggle.addEventListener('click', function(){
//   nav.classList.toggle('blablabla');
// })

const icons = document.querySelector('#icons');
const nav = document.querySelector('#nav');

icons.addEventListener('click', ()=> {
  nav.classList.toggle('navactive');
});