jQuery(function ($) {
  'use strict';

  // Range Input
  $('.range_input').each(function () {
    $(this).on('input', function () {
      let newVal =
        (($(this).val() - $(this).attr('min')) * 100) /
        ($(this).attr('max') - $(this).attr('min'));
      let newPos = 10 - newVal * 0.2;
      $(this)
        .parents('.range-wrap')
        .find('.range-value')
        .html(`<span>${$(this).val()}</span>`);
      $(this)
        .parents('.range-wrap')
        .find('.range-value')
        .css('left', `calc(${newVal}% + (${newPos}px))`);
    });
  });

  // Logo Upload
  $('#_upload__logo').click(function (e) {
    e.preventDefault();
    var login_logo;

    if (login_logo) {
      login_logo.open();
      return;
    }
    // Extend the wp.media object
    login_logo = wp.media.frames.file_frame = wp.media({
      title: 'Select logo',
      button: {
        text: 'Select',
      },
      multiple: false,
    });

    // When a file is selected, grab the URL and set it as the text field's value
    login_logo.on('select', function () {
      var attachment = login_logo.state().get('selection').first().toJSON();
      $('#dpcl_custom_logo').val(attachment.url);
      $('._logo__preview').html(
        `<img class="logo_preview_img" src="${attachment.url}">`
      );
    });
    // Open the upload dialog
    login_logo.open();
  });

  $('#_remove__logo').on('click', function (e) {
    e.preventDefault();
    $('._logo__preview').html('');
    $('#dpcl_custom_logo').val('');
  });

  // Color plates
  $('#dpcl_body_bg_color').wpColorPicker({ _hasAlpha: true });
  $('#dpcl_body_color').wpColorPicker();
  $('#dpcl_form_background_color').wpColorPicker();
  $('#dpcl_form_box_shadow_color').wpColorPicker();
  $('#dpcl_login_btn_background_color').wpColorPicker();
  $('#dpcl_login_btn_hover_bg_color').wpColorPicker();
  $('#dpcl_link_color').wpColorPicker();
  $('#dpcl_link_hover_color').wpColorPicker();
  $('#dpcl_input_focus_border_color').wpColorPicker();
  $('#dpcl_input_focus_shadow_color').wpColorPicker();
});
