/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

/**Lightbox */
// Sélectionnez toutes les images dans la classe '.image-container'
var images = document.querySelectorAll('.image-container img');

// Sélectionnez la boîte modale (popup) et le bouton pour la fermer
var modal = document.querySelector('.popup-image');
var closeButton = document.querySelector('.popup-image span');

// Ajoutez un gestionnaire d'événement click à chaque image
images.forEach(function(image) {
  image.addEventListener('click', function() {
    // Affiche la boîte modale
    modal.style.display = 'block';

    // Définit la source de l'image de la boîte modale sur celle de l'image cliquée
    var imageSrc = image.getAttribute('src');
    var modalImage = modal.querySelector('img');
    modalImage.setAttribute('src', imageSrc);
  });
});

// Ajoutez un gestionnaire d'événement click au bouton de fermeture de la boîte modale
closeButton.addEventListener('click', function() {
  // Masque la boîte modale
  modal.style.display = 'none';
});

/**burger menu */
icons.addEventListener('click', ()=>{
  nav.classList.toggle("navactive");
})