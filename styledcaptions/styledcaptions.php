<?php
/*
Plugin Name: Styled Captions
Description: Plugin for styling captions
Author: Jasper Scaff
Version: 2.0
*/

class StyledCaptions {

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
        add_action( 'dbx_post_advanced', array( $this, 'changeImageEditorAction' ) );
        add_action( 'print_media_templates', array( $this, 'redirectMediaEditPage' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'addJs' ) );
        
        add_filter( 'image_size_names_choose', array( $this, 'changeImageSizeNamesOrder' ) );
    }
    /**
     * Change ImageEditor action.
     */
    function changeImageEditorAction( $post ) {
        remove_action( 'edit_form_after_title', 'edit_form_image_editor' );

        add_action( 'edit_form_after_title', array( $this, 'updateImageEditor' ) );
    }

    /**
     * Redirect MediaPage by Grid.
     */
    function redirectMediaEditPage() {
        ?>
        <script type="text/javascript">
            jQuery( document ).ajaxComplete(function( event, xhr, settings ) {
                setTimeout(function() {
                    jQuery("body.upload-php .attachment-preview").off('click').on('click', function(e) {
                        e.preventDefault();
                        var data_id = jQuery(this).parent().attr('data-id');
                        window.location.href = 'post.php?post=' + data_id + '&action=edit';
                        return false;
                    });
                }, 400);
            });            
        </script>
    <?php
    }
    
    function addJs() {
        wp_enqueue_script( 'styled-captions', plugins_url( 'js/styled-captions.js', __FILE__ ), array( 'media-views' ) );
    }

    /**
     * Customize ImageEditor.
     */
    function updateImageEditor( $post ) {

        if ( $post->post_type !=='attachment' ) return;

        $open = isset( $_GET['image-editor'] );
        if ( $open )
            require_once ABSPATH . 'wp-admin/includes/image-edit.php';

        $thumb_url = false;
        if ( $attachment_id = intval( $post->ID ) )
            $thumb_url = wp_get_attachment_image_src( $attachment_id, array( 900, 450 ), true );

        $alt_text = get_post_meta( $post->ID, '_wp_attachment_image_alt', true );

        $att_url = wp_get_attachment_url( $post->ID ); ?>
        <div class="wp_attachment_holder wp-clearfix">
            <?php
            if ( wp_attachment_is_image( $post->ID ) ) :
                $image_edit_button = '';
                if ( wp_image_editor_supports( array( 'mime_type' => $post->post_mime_type ) ) ) {
                    $nonce = wp_create_nonce( "image_editor-$post->ID" );
                    $image_edit_button = "<input type='button' id='imgedit-open-btn-$post->ID' onclick='imageEdit.open( $post->ID, \"$nonce\" )' class='button' value='" . esc_attr__( 'Edit Image' ) . "' /> <span class='spinner'></span>";
                }
                ?>

                <div class="imgedit-response" id="imgedit-response-<?php echo $attachment_id; ?>"></div>

                <div<?php if ( $open ) echo ' style="display:none"'; ?> class="wp_attachment_image wp-clearfix" id="media-head-<?php echo $attachment_id; ?>">
                    <p id="thumbnail-head-<?php echo $attachment_id; ?>"><img class="thumbnail" src="<?php echo set_url_scheme( $thumb_url[0] ); ?>" style="max-width:100%" alt="" /></p>
                    <p><?php echo $image_edit_button; ?></p>
                </div>
                <div<?php if ( ! $open ) echo ' style="display:none"'; ?> class="image-editor" id="image-editor-<?php echo $attachment_id; ?>">
                    <?php if ( $open ) wp_image_editor( $attachment_id ); ?>
                </div>
            <?php
            elseif ( $attachment_id && wp_attachment_is( 'audio', $post ) ):

                wp_maybe_generate_attachment_metadata( $post );

                echo wp_audio_shortcode( array( 'src' => $att_url ) );

            elseif ( $attachment_id && wp_attachment_is( 'video', $post ) ):

                wp_maybe_generate_attachment_metadata( $post );

                $meta = wp_get_attachment_metadata( $attachment_id );
                $w = ! empty( $meta['width'] ) ? min( $meta['width'], 640 ) : 0;
                $h = ! empty( $meta['height'] ) ? $meta['height'] : 0;
                if ( $h && $w < $meta['width'] ) {
                    $h = round( ( $meta['height'] * $w ) / $meta['width'] );
                }

                $attr = array( 'src' => $att_url );
                if ( ! empty( $w ) && ! empty( $h ) ) {
                    $attr['width'] = $w;
                    $attr['height'] = $h;
                }

                $thumb_id = get_post_thumbnail_id( $attachment_id );
                if ( ! empty( $thumb_id ) ) {
                    $attr['poster'] = wp_get_attachment_url( $thumb_id );
                }

                echo wp_video_shortcode( $attr );

            endif; ?>
        </div>
        <div class="wp_attachment_details edit-form-section">
            <p>
                <label for="attachment_caption"><strong><?php _e( 'Caption' ); ?></strong></label><br />
                <?php
                $content = htmlspecialchars_decode($post->post_excerpt);
                $editor_id = 'attachment_caption';
                $settings = array( 'media_buttons'=> false,'textarea_name' => 'excerpt' );
                wp_editor( $content, $editor_id, $settings );
                ?>
            </p>

            <?php if ( 'image' === substr( $post->post_mime_type, 0, 5 ) ) : ?>
                <p>
                    <label for="attachment_alt"><strong><?php _e( 'Alternative Text' ); ?></strong></label><br />
                    <input type="text" class="widefat" name="_wp_attachment_image_alt" id="attachment_alt" value="<?php echo esc_attr( $alt_text ); ?>" />
                </p>
            <?php endif; ?>

            <?php
            $quicktags_settings = array( 'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,close' );
            $editor_args = array(
                'textarea_name' => 'content',
                'textarea_rows' => 5,
                'media_buttons' => false,
                'tinymce' => false,
                'quicktags' => $quicktags_settings,
            );
            ?>

            <label for="attachment_content"><strong><?php _e( 'Description' ); ?></strong><?php
                if ( preg_match( '#^(audio|video)/#', $post->post_mime_type ) ) {
                    echo ': ' . __( 'Displayed on attachment pages.' );
                } ?></label>
            <?php wp_editor( $post->post_content, 'attachment_content', $editor_args ); ?>

        </div>
        <?php
        $extras = get_compat_media_markup( $post->ID );
        echo $extras['item'];
        echo '<input type="hidden" id="image-edit-context" value="edit-attachment" />' . "\n";
    }

    function changeImageSizeNamesOrder($args) {
        return array(
            'full'      => __('Full Size'),
            'large'     => __('Large'),
            'medium'    => __('Medium'),
            'thumbnail' => __('Thumbnail'),
        );
    }
    
    //function changeImageSize($out, $pairs, $atts, $shortcode) {
//        if (!$atts['size']) {
//            $out['size'] = 'full';
//        }
//        return $out;
//    }

}

StyledCaptions::get_instance();