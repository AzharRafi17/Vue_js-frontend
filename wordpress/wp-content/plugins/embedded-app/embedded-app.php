<?php
/**
 * Plugin Name:         vue-wp-plugin
 * Plugin URI:          C:\Users\TSD\vue-crash-2024\vue-wp-plugin\readme.txt
 * Description:         Some great app that I made and it's so great it'll make your life great!
 * Version:             1.0.0
 * Author:              Azhar Rafi
 * 
 * Be sure to rename the folder this file is in and this file to match what your plugin will be called. The names must be the same so WordPress knows where to look.
 */


// Shortcode to embed Vue.js app
function load_vuescripts() {
    wp_enqueue_style( 'vue_wp_styles', plugin_dir_url( __FILE__ ) . 'dist/css/app.css' );
    wp_register_script( 'vue_wp_compiled', plugin_dir_url( __FILE__ ) . 'dist/js/app.js', array(), '', true );
}

add_action( 'wp_enqueue_scripts', 'load_vuescripts' );

function attach_vue() {
    wp_enqueue_script( 'vue_wp_compiled' );

    return "<div id='app'></div>";
}

add_shortcode( 'vue_wp', 'attach_vue' );