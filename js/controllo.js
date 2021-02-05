$(function(){
    $("#user").keyup(function(event) /*Quando rilascio un tasto richiama la funzione*/
    {
        var username = $(this).val();
        $.ajax({           /*Richiamo AJAX*/
            type: "POST", /*Metodo passaggio parametri utilizzato*/
            url: "controllo_utente.php",  /*Faccio riferimento al file php che contatta il database*/
            data: { user : username },
            success: function(data){
        if(data == "Errore" || data == "Vuoto")   /*PHP restituisce uno di questi 2 valori*/
				{
          document.getElementById('invia').setAttribute("disabled", "");   /*Disabilito il bottone*/
					if(data == "Errore")
					{
						document.getElementById('esito').innerHTML="Il nome &egrave; gi&agrave; in uso!";
					}
				}
				else
				{
          document.getElementById('invia').removeAttribute("disabled");   /*Attivo il bottone*/
					document.getElementById('esito').innerHTML="Username disponibile";
				}
      }
    });
  });
});
