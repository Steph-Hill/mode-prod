$(document).ready(function() {
    $(window).scroll(function() {
      var homeContent = $('#home-content'); // Remplacez par l'ID de votre zone d'accueil
  
      if (homeContent.offset().top <= $(window).scrollTop() + $('.custom-navbar').outerHeight()) {
        $('body').addClass('home-content-touch');
      } else {
        $('body').removeClass('home-content-touch');
      }
    });
  });
// Récupérez une référence vers votre modal et l'élément sur lequel vous souhaitez définir le focus
const editSalonModal = document.getElementById('editSalonModal{{ salon.id }}');
const recipientNameInput = editSalonModal.querySelector('#recipient-name');

// Ajoutez un écouteur d'événements lorsque le modal est affiché
editSalonModal.addEventListener('shown.bs.modal', () => {
  // Définissez le focus sur l'élément 'recipient-name' lorsque le modal est affiché
  recipientNameInput.focus();
});



    // Sélectionnez la div
    var scrollableDiv = document.getElementById("scrollable-div");

    // Vérifiez si la hauteur du contenu est inférieure à la hauteur maximale
    if (scrollableDiv.scrollHeight <= scrollableDiv.clientHeight) {
        // Si c'est le cas, supprimez la barre de défilement
        scrollableDiv.style.overflowY = "hidden";
    }
