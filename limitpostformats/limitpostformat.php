 <?php
/*
Plugin Name: Limit-Post-Format 
Description: Plugin for Limit available formats to Standard and Gallery
Author: Jasper Scaff
Version: 1.0
*/
class LimitPostFormat {
/** Refers to a single instance of this class. */
private static $instance = null;
/**
* Creates or returns an instance of this class.
*
* @return  A single instance of this class.
*/
public static function get_instance() {
if ( null == self::$instance ) {
self::$instance = new self;
}
return self::$instance;
}
/**
* Initializes the plugin by setting localization, filters, and administration functions.
*/
private function __construct() {
add_action( 'admin_init', array( $this, 'SetLimit' ) );
} 
/**
* Set the Post Formats with Standard and Gallery for only Editors
* 
*/
function SetLimit() {   
global $current_user;
if ( is_user_logged_in() ) {
$userRole = $current_user->roles;         
if (in_array('editor', $userRole)) {              
remove_theme_support( 'post-formats', array( 'audio', 'image', 'link', 'quote', 'video' ) );
add_theme_support('post-formats', array( 'standard', 'gallery' ) );       
}
}
}
}
LimitPostFormat::get_instance();
