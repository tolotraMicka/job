// $(document).ready(function() {
//     $('#type').val("recruteur").click(function() {
//         $('.hide-label').show();
//         $('.hide-input').show();
//     });
    
// });
// onChange qui affiche l'input société dans le registration twig
$(document).ready(function() {
    $('#type').on("change", function() {
      var value = $(this).val();
      console.log(value);
      if (value == "recruteur") {
        $('.hide-label').show();
        $('.hide-input').show();
      } else {
        $('.hide-label').hide();
        $('.hide-input').hide();
      }
    });
  });
  



