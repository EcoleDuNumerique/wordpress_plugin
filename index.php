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

            $avis_html .= "<div class='simple-avis'>";
                $avis_html .= "<img src='".$thumbnail_url."' />";
                $avis_html .= "<div class='right-content'>";
                    $avis_html .= "<h3>".$title."</h3>";
                    $avis_html .= "<p>".$content."</p>";
                $avis_html .= "</div>";
            $avis_html .= "</div>";
        }

    }

    $avis_html .= "</div>";

    return $avis_html;

}