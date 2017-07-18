var ucf_post_list_upload = function($) {
  $('.ucf_post_list_fallback_image_upload').click(function(e) {
    e.preventDefault();

    var uploader = wp.media({
      title: 'Post List Fallback Image',
      button: {
        text: 'Upload Image'
      },
      multiple: false
    })
    .on('select', function() {
      var attachment = uploader.state().get('selection').first().toJSON();
      $('.ucf_post_list_fallback_image_preview').attr('src', attachment.url);
      $('.ucf_post_list_fallback_image').val(attachment.id);
    })
    .open();
  });
};

jQuery(document).ready(function ($) {
  ucf_post_list_upload($);
});
