<?php
/*
    Plugin Name: Plugin Avis
    Description: Un pluggin permettant d'afficher des avis sur des pages
    Version: 0.0.1
    Author: Pierre Mar
    License: free
*/

//Add script
add_action("wp_enqueue_scripts", "custom_avis_scripts");
function custom_avis_scripts(){
    wp_enqueue_script("avis_script", plugin_dir_url("./")."/avis/script.js", array( "jquery" ));
    wp_enqueue_style( "avis_styles", plugin_dir_url( "./" )."/avis/styles.css" );
}

//Actions hooks
add_action( "init", "create_avis_post_type");
function create_avis_post_type(){

    register_post_type( "avis", [

        "labels" => [
            "name" => "Avis",
            "singular_name" => "Avis",
            "all_items " => "Tous les avis",
            "add_new " => "Ajouter un avis"
        ],
        "description" => "Un avis sur votre entreprise",
        "show_in_menu" => true,
        "public" => true,
        "menu_icon" => "dashicons-star-half",
        "menu_position" => 2,
        "supports" => [
            "title",
            "editor",
            "revisions",
            "thumbnail"
        ]

    ] );

}

//Ajoute un shortcode
add_shortcode( "avis", "display_shortcode" );
function display_shortcode( $atts ){

    $avis = new WP_Query( [
        "post_type" => "avis"
    ] );

    $avis_html = "<div id='avis'>";

    if( $avis->have_posts() ){

        while( $avis->have_posts() ){

            $avis->the_post();

            $title = get_the_title();
            $content = get_the_content();
            $thumbnail_url = get_the_post_thumbnail_url( null, "thumbnail" );

            if( get_post_meta( $avis->post->ID, "note" ) )
                $note = get_post_meta( $avis->post->ID, "note" )[0];
            else 
                $note = false;

            $avis_html .= "<div class='simple-avis'>";
                $avis_html .= "<img src='".$thumbnail_url."' />";
                $avis_html .= "<div class='right-content'>";
                    $avis_html .= "<h3>".$title."</h3>";
                    $avis_html .= "<p>".$content."</p>";
                    if( $note != false ){
                        $avis_html .= "<i> Note: ".$note."/5 </i>";
                    }
                $avis_html .= "</div>";
            $avis_html .= "</div>";
        }

    }

    $avis_html .= "</div>";

    return $avis_html;

}

//Ajout de champs personnalisé : notes d'avis
add_action("add_meta_boxes", "register_notes_fields");
function register_notes_fields(){

    //Ajoute un champ pour un type de post
    add_meta_box( "notes", "Notes", "display_note_field", "avis" );

}

//Callback d'affichage du champs
function display_note_field(){
?>
    <label>
        <input type="radio" value="1" name="note" <?= note_checked(1) ?> ><span> 1 </span>
    </label>
    <label>
        <input type="radio" value="2" name="note" <?= note_checked(2) ?> ><span> 2 </span>
    </label>
    <label>
        <input type="radio" value="3" name="note" <?= note_checked(3) ?> ><span> 3 </span>
    </label>
    <label>
        <input type="radio" value="4" name="note" <?= note_checked(4) ?> ><span> 4 </span>
    </label>
    <label>
        <input type="radio" value="5" name="note" <?= note_checked(5) ?> ><span> 5 </span>
    </label>

<?php
}

function note_checked( $val ){ 
    global $post;

    if( !get_post_meta($post->ID, "note") ){
        return "";
    }

    if( $val == get_post_meta($post->ID, "note")[0] ){
        return "checked";
    }
}

//Saving post 
add_action("save_post", "update_note_value");

//Ici on va créer ou mettre à jour une valeur customisé (n'existe pas par default)
function update_note_value(){

    global $post; //Récupère le post actuel

    if( get_post_meta( $post->ID, "note") ) {
        update_post_meta( $post->ID, "note", $_POST["note"] );
    }
    else {
        add_post_meta( $post->ID, "note",$_POST["note"] );
    }

}