$(document).ready(function(){
        $('.deleteadmin').click(function(event) {
            event.preventDefault();
            var r=confirm("Vous voulez vraiment Supprimer ?");
            if (r==true)   {  
            window.location = $(this).attr('href');
            }
        }); 
    
});

   
