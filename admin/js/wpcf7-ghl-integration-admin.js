jQuery($ => {
  $("#contactForm7GHL").on("submit", e => {
    e.preventDefault();
    $.post(wpcf7ghl.ajax_url, {
      data: $(e.target).serialize(),
      action: "wpcf7ghl_admin_form_submission",
      nonce: wpcf7ghl.nonce
    })
    .done(response => {
      Swal.fire({
        title: "Success!",
        text: response.data,
        icon: "success",
        confirmButtonText: "Accept"
      });
    })
    .fail(xhr => {
      const error = JSON.parse(xhr.responseText);
      Swal.fire({
        title: "Error!",
        text: error.data,
        icon: "error",
        confirmButtonText: "Accept"
      });
    });
  });
});
