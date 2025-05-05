



// Script pour basculer l'affichage du sous-menu du compte
document.getElementById('account-icon').addEventListener('click', function(event) {
    var dropdown = document.getElementById('account-dropdown');
    dropdown.classList.toggle('show'); // Ajouter/supprimer la classe 'show'
    event.stopPropagation(); // Empêcher la propagation de l'événement de clic
});

// Fermer le sous-menu si l'utilisateur clique en dehors de celui-ci
window.addEventListener('click', function(e) {
    var dropdown = document.getElementById('account-dropdown');
    if (!dropdown.contains(e.target)) { // Vérifie si le clic est en dehors du menu déroulant
        dropdown.classList.remove('show');
    }
});


const cartModal = document.getElementById('cart-modal');
const addToCartButtons = document.querySelectorAll('.add-to-cart');
const closeCartModalButton = document.querySelector('#cart-modal .close-popup');
const cartIcon = document.querySelector('.cart');


    function showCartModal() {
        cartModal.style.display = 'block';
    }

    // Fermer la popup du panier lorsqu'on clique sur "X"
    closeCartModalButton.addEventListener('click', () => {
        cartModal.style.display = 'none';
    });

    // Fermer la popup en cliquant en dehors de celle-ci
    window.addEventListener('click', (event) => {
        if (event.target === cartModal) {
            cartModal.style.display = 'none';
        }
    });

    // Afficher le panier lorsqu'on clique sur l'icône du panier
    cartIcon.addEventListener('click', showCartModal);




    

 






