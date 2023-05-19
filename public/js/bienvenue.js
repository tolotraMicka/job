var drop_Recherche = document.getElementById("drop-menu");
var dropResearch = document.getElementById("dropdown");

  function menudrop(){
  drop_Recherche.style.display = "block";
   // Ajouter un écouteur d'événement pour "click" sur la fenêtre
}

/* dropdown close after click on window*/
window.onclick = function (e) {
   if (!e.target.matches('.dropdown')) {
      drop_Recherche.style.display="none";
   }
 }
