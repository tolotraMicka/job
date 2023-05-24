// $(document).ready(function() {
//     $('#type').val("recruteur").click(function() {
//         $('.hide-label').show();
//         $('.hide-input').show();
//     });
    
// });

$(document).ready(function() {
    $('#type').on("change", function() {
      var value = $(this).val();
      if (value == 1) {
        $('.hide-label').show();
        $('.hide-input').show();
      } else {
        $('.hide-label').hide();
        $('.hide-input').hide();
      }
    });
  });
  



