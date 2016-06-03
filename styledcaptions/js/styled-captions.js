if (typeof wp.media != 'undefined' && typeof wp.media.view != 'undefined') {
    wp.media.view.settings.galleryDefaults = jQuery.extend( {}, wp.media.view.settings.galleryDefaults, {
        size: 'full'
    } );
}