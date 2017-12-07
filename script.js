const $ = jQuery;

$(document).ready(function(){

    var $avis = $("#avis");

    if( $avis.length < 1 ){ return; }

    //slider d'avis 
    $all_avis = $(".simple-avis");
    $current_avis = $all_avis.first();

    $all_avis.hide();
    $current_avis.show();

    setInterval(function(){
        $current_avis.fadeOut(500, /* callback fin 500ms */function(){
            //prochain élément
            $current_avis = $current_avis.next();
            
            //vérifie que l'élément existe
            if( $current_avis.length < 1 ){
                $current_avis = $all_avis.first();
            }

            $current_avis.fadeIn(500);
        });
    }, 3000);


});