const UCFPostListUpload = function ($) {
  $('.ucf_post_list_fallback_image_upload').click((e) => {
    e.preventDefault();

    const uploader = wp.media({
      title: 'Post List Fallback Image',
      button: {
        text: 'Upload Image'
      },
      multiple: false
    })
      .on('select', () => {
        const attachment = uploader.state().get('selection').first().toJSON();
        $('.ucf_post_list_fallback_image_preview').attr('src', attachment.url);
        $('.ucf_post_list_fallback_image').val(attachment.id);
      })
      .open();
  });
};

jQuery(document).ready(($) => {
  UCFPostListUpload($);
});
