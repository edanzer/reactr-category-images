jQuery(function($){
    $('.imagely-category-image-button').click(function(e){
        e.preventDefault();
  		console.log('clicked');
        var button = $(this),
        imagely_uploader = wp.media({
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
            var attachment = imagely_uploader.state().get('selection').first().toJSON();
            $('#imagely-category-image').val(attachment.url);
        })
        .open();
    });
});