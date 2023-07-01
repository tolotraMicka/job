// $(document).ready(function() {
//     $('#type').val("recruteur").click(function() {
//         $('.hide-label').show();
//         $('.hide-input').show();
//     });
    
// });

$(document).ready(function() {
      $('#type').on("change", function() {
        var value = $(this).val();
        // alert(value);
        if (value == "recruteur") {
          $('.hide-label').show();
          $('.hide-input').show();
        } else {
          $('.hide-label').hide();
          $('.hide-input').hide();
        }
      });
    });

/**----------------------dropdown espace particulier sur l'image------------------------------ */
$(document).ready(function() {
  $("#dropdown-image").click(function(e) {
      e.stopPropagation(); // Empêche la propagation de l'événement click vers les éléments parents
      $("#dropmenu_particulier").toggle(); // Alterne entre l'affichage et la non-affichage du menu
  });
  $(window).click(function() {
      $("#dropmenu_particulier").hide(); // Masque le menu lorsque l'utilisateur clique en dehors de celui-ci
  });
});
  
  



