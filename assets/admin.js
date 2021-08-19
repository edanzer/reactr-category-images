jQuery(function($){
    $('.reactr-category-image-button').click(function(e){
        e.preventDefault();
  		console.log('clicked');
        var button = $(this),
        reactr_uploader = wp.media({
            title: 'Custom image',
            library : {
                //uploadedTo : wp.media.view.settings.post.id,
                type : 'image'
            },
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = reactr_uploader.state().get('selection').first().toJSON();
            $('#reactr-category-image').val(attachment.url);
        })
        .open();
    });
});