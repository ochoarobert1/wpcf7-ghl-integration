jQuery(($) => {
  $("#contactForm7GHL").on("submit", (e) => {
    e.preventDefault();
    $.post(wpcf7ghl.ajax_url, {
      data: $(e.target).serialize(),
      action: "wpcf7ghl_admin_form_submission",
      nonce: wpcf7ghl.nonce,
    })
      .done((response) => {
        Swal.fire({
          title: response.data.title,
          text: response.data.message,
          icon: "success",
          confirmButtonText: response.data.accept_btn,
        });
      })
      .fail((xhr) => {
        const error = JSON.parse(xhr.responseText);
        Swal.fire({
          title: error.data.title,
          text: error.data.message,
          icon: "success",
          confirmButtonText: error.data.accept_btn,
        });
      });
  });

  // Field repeater functionality
  let fieldIndex = 0;
  let initialized = false;

  function initRepeater() {
    if (initialized || $("#wpcf7GHLRepeater").length === 0) return;

    initialized = true;

    // Set field index based on existing fields
    fieldIndex = $(".form-wrapper").length;

    // Add initial field if none exist
    if (fieldIndex === 0) {
      addField();
    }

    // Bind events
    $(document)
      .off("click.repeater")
      .on("click.repeater", "#addField", function (e) {
        e.preventDefault();
        addField();
      });

    $(document)
      .off("click.remove")
      .on("click.remove", ".remove-field", function (e) {
        e.preventDefault();
        if ($(".form-wrapper").length > 1) {
          $(this).closest(".form-wrapper").remove();
        }
      });
  }

  function addField() {
    const html = `
      <div class="form-wrapper" data-index="${fieldIndex++}">
        <div class="form-group">
          <input name="wpcf7-ghl-cf7-field[]" type="text" placeholder="e.g., [your-name]" />
          <label>Contact Form 7 Field</label>
        </div>
        <div class="form-group">
          <input name="wpcf7-ghl-field[]" type="text" placeholder="e.g., firstName" />
          <label>GoHighLevel Field</label>
        </div>
        <div class="form-group-buttons">
          <button type="button" class="remove-field button button-link-delete">Remove</button>
        </div>
      </div>
    `;
    $("#wpcf7GHLRepeater").append(html);
  }

  // Initialize repeater
  $(document).ready(initRepeater);
  $(document).on("wpcf7:tabselected", initRepeater);
  setTimeout(initRepeater, 1000);
});
