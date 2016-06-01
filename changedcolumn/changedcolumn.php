<?php
/*
Plugin Name: Change default column
Description: Plugin for changing default number of Column
Author: Jasper Scaff
Version: 1.0
*/

class ChangedColumn {

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
        
        add_filter( 'media_view_settings', array( $this, 'changeColumnNumOrder' ), 10, 2 );
    }
    
    /**
    * Change the default number of Columns.
    */
    function changeColumnNumOrder($settings, $post) {
        $settings['galleryDefaults']['columns'] = 1;
        return $settings;
    }
}

ChangedColumn::get_instance();