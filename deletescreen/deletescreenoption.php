 <?php
/*
Plugin Name: DeleteFieldScreenOptions
Description: Delete field from Screen Options for Editors
Author: Jasper Scaff
Version: 1.0
*/

class DeleteFieldScreenOptions {

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
 //       add_action( 'admin_menu', array( $this, 'DeleteField' ) );
	    add_filter('manage_edit-post_columns', array( $this, 'deletefield' ), 1000, 1);  
    }
    function deletefield($columns) {
        global $current_user;
        if ( is_user_logged_in() ) {
            $userRole = $current_user->roles;         
            if (in_array('editor', $userRole)) {
				$default_columns=array('author','tags','categories','title');
				foreach($columns as $key =>$value) {
					if(! in_array($key,$default_columns))
					{
						unset($columns[$key]);
					}
				}
				return $columns;
            }
        }
    }	
}

DeleteFieldScreenOptions::get_instance();
