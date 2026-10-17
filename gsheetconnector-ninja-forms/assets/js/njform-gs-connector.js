jQuery(document).ready(function () {
  /**
   * verify the api code
   * @since 1.0
   */
  jQuery(document).on("click", "#save-njform-gs-code", function (event) {
    event.preventDefault();
    jQuery(".loading-sign").addClass("loading");
    jQuery("#save-njform-gs-code")
      .prop("disabled", true)
      .addClass("common-disable");
    var data = {
      action: "verify_njforms_gs_integation",
      code: jQuery("#njforms-setting-google-access-code").val(),
      security: jQuery("#gs-ajax-nonce").val(),
    };
    jQuery.post(ajaxurl, data, function (response) {
      if (!response.success) {
        jQuery(".loading-sign").removeClass("loading");
        jQuery("#gs-nj-validation-message").empty();
        jQuery(
          "<div class='error-message gsc-msg gsc-error fw-400 text-dark text-center mt-10 pt-10 pb-10 manual-margin'>Invalid access code entered.</div>",
        ).appendTo("#gs-nj-validation-message");
      } else {
        jQuery(".loading-sign").removeClass("loading");
        jQuery("#gs-nj-validation-message").empty();
        jQuery(
          "<div class='gs-valid-message gsc-msg gsc-success fw-400 text-dark text-center mt-10 pt-10 pb-10 manual-margin'>Your Google Access Code is Authorized and Saved.</div>",
        ).appendTo("#gs-nj-validation-message");

        setTimeout(function () {
          window.location.href = jQuery("#redirect_auth_ninjaforms").val();
        }, 1000);
      }
    });
  });

  function html_decode(input) {
    var doc = new DOMParser().parseFromString(input, "text/html");
    return doc.documentElement.textContent;
  }

  /**
   * Clear debug
   */
  jQuery(document).on("click", ".debug-clear-kk", function () {
    jQuery(".clear-loading-sign").addClass("loading");
    var data = {
      action: "gs_clear_logs",
      security: jQuery("#gs-ajax-nonce").val(),
    };
    jQuery.post(ajaxurl, data, function (response) {
      var clear_msg = response.data;
      if (response.success) {
        jQuery(".clear-loading-sign").removeClass("loading");
        jQuery("#njgs-validation-message").empty();
        jQuery(
          "<span class='gs-valid-message'>" + clear_msg + "</span>",
        ).appendTo("#njgs-validation-message");
        setTimeout(function () {
          location.reload();
        }, 1000);
      }
    });
  });

  /**
   * deactivate the api code
   * @since 1.0
   */
  jQuery(document).on("click", "#nj-deactivate-log", function () {
    jQuery(".loading-sign-deactive").addClass("loading");
    jQuery("#nj-confirm-deactive-popup-free").addClass("d-none");
    var data = {
      action: "deactivate_nj_integation",
      security: jQuery("#gs-ajax-nonce").val(),
    };
    jQuery.post(ajaxurl, data, function (response) {
      if (response == -1) {
        return false; // Invalid nonce
      }

      if (!response.success) {
        alert("Error while deactivation");
        jQuery(".loading-sign-deactive").removeClass("loading");
        jQuery("#gs-nj-deactivate-message").empty();
      } else {
        jQuery(".loading-sign-deactive").removeClass("loading");
        jQuery("#gs-nj-deactivate-message").empty();
        jQuery(
          "<div class='nf-valid-message gsc-msg gsc-success fw-400 text-dark text-center mt-10 pt-10 pb-10 manual-margin'>Your account is removed, now reauthenticate to configure Ninja Forms to Google Sheet.</div>",
        ).appendTo("#gs-nj-deactivate-message");
        setTimeout(function () {
          location.reload();
        }, 1000);
      }
    });
  });

  /**
   * Display Error logs
   */
  jQuery(document).on("click", ".closenjformView", function () {
    // Change button text to "View" and remove the class
    jQuery(this).text("View").removeClass("closenjformView");

    // Add the class back to show the logs
    jQuery(this).addClass("njgsc-logs");

    // Hide the nj-system-Error-logs div
    jQuery(".nj-system-Error-logs").hide();
  });

  jQuery(document).on("click", ".njgsc-logs", function () {
    // Change button text to "Close" and update the class
    jQuery(this)
      .text("Close")
      .addClass("closenjformView")
      .removeClass("njgsc-logs");

    // Show the nj-system-Error-logs div
    jQuery(".nj-system-Error-logs").show();
  });

  jQuery(document).ready(function ($) {
    // Hide .nj-system-Error-logs initially
    $(".nj-system-Error-logs").hide();

    // Prevent the nj-system-Error-logs from closing when clicked inside
    $(".nj-system-Error-logs").on("click", function (e) {
      e.stopPropagation(); // Prevent the click event from propagating further
    });
  });

  /**
   * Clear debug for system status tab
   */
  jQuery(document).on("click", ".clear-content-logs-nj", function () {
    jQuery(".clear-loading-sign-logs-nj").addClass("loading");
    var data = {
      action: "nj_clear_debug_logs",
      security: jQuery("#gs-ajax-nonce").val(),
    };
    jQuery.post(ajaxurl, data, function (response) {
      if (response == -1) {
        return false; // Invalid nonce
      }

      if (response.success) {
        jQuery(".clear-loading-sign-logs-nj").removeClass("loading");
        jQuery(".clear-content-logs-msg-nj").html("Logs are cleared.");
        setTimeout(function () {
          location.reload();
        }, 1000);
      }
    });
  });
});

// Msg Hide ///

jQuery(document).ready(function ($) {
  // Check if the message has already been hidden by looking in localStorage
  if (localStorage.getItem("googleDriveMsgHidden") === "true") {
    jQuery("#google-drive-msg").hide(); // Hide the message if it's already hidden
  }

  // On button click, hide the #google-drive-msg div and store the hidden state in localStorage
  jQuery(".button_njformgsc").on("click", function () {
    jQuery("#google-drive-msg").hide(); // Hide the message
    localStorage.setItem("googleDriveMsgHidden", "true"); // Save the hidden state in localStorage
  });

  // On #deactivate-log click, show the #google-drive-msg div and clear localStorage
  jQuery("#nj-deactivate-log").on("click", function () {
    jQuery("#google-drive-msg").show(); // Show the message
    localStorage.removeItem("googleDriveMsgHidden"); // Remove the hidden state from localStorage
  });
});

/** hide notice  */
jQuery(document).on("click", "#nf-pro-dismiss-header-notice", function () {
  var nonce = jQuery("#gs-ajax-nonce").val();
  jQuery("#pro-notice-bar").hide();
  jQuery.post(ajaxurl, {
    action: "dismiss_nf_pro_notice",
    nonce: nonce,
  });
});

/** Notification slider  */
/** notificatin slider arrow button will hide when slider is 1 or 0 */
jQuery(document).ready(function ($) {
  if ($(".notification-nf-slide").length <= 1) {
    $(".notification-nf-slider-arrows").hide();
  }

  if ($(".notification-nf-slide").length == 0) {
    $(".notification-nf-notice-slider").hide();
  }

  $(".nf-free-counter").each(function () {
    let $this = $(this);
    let countTo = parseFloat($this.attr("data-count"));

    $({ countNum: 0 }).animate(
      {
        countNum: countTo,
      },
      {
        duration: 2500,
        easing: "swing",

        step: function () {
          if (countTo % 1 !== 0) {
            $this.text(this.countNum.toFixed(1));
          } else {
            $this.text(Math.floor(this.countNum));
          }
        },

        complete: function () {
          if (countTo % 1 !== 0) {
            $this.text(countTo.toFixed(1));
          } else {
            $this.text(countTo);
          }
        },
      },
    );
  });
});

jQuery(document).ready(function ($) {
  let totalSlides = $(
    ".notification-nf-slider-track .notification-nf-slide",
  ).length;

  if (totalSlides <= 1) {
  }
});

function showNextSlide(currentSlide) {
  var track = currentSlide.closest(".notification-nf-slider-track");
  var slides = track.find(".notification-nf-slide");
  var currentIndex = slides.index(currentSlide);
  var nextIndex = currentIndex + 1;

  currentSlide.remove();

  slides = track.find(".notification-nf-slide");

  if (slides.length > 0) {
    if (nextIndex >= slides.length) {
      nextIndex = 0;
    }
    slides.hide().eq(nextIndex).show();
  }
  location.reload();
}

/** For JQuery for Dismiss notification */
jQuery(document).on(
  "click",
  ".nf-review-close, .nf-review-dismiss-btn  , .nf-showpro-close, .nf-addons-close, .nf-enhance-btn-later, .nf-enhance-close",
  function () {
    var key = jQuery(this).data("key");
    var currentSlide = jQuery(this).closest(".notification-nf-slide");

    jQuery.post(
      ajaxurl,
      {
        action: "nf_dismiss_notice",
        key: key,
        security: jQuery("#gs-ajax-nonce").val(),
      },
      function () {
        showNextSlide(currentSlide);
      },
    );
  },
);

/** For JQuery for snooze notification */
jQuery(document).on(
  "click",
  ".nf-review-btn-later, .nf-Showpro-btn-later, .nf-addons-btn-later",
  function () {
    var key = jQuery(this).data("key");
    var currentSlide = jQuery(this).closest(".notification-nf-slide");

    jQuery.post(
      ajaxurl,
      {
        action: "nf_snooze_notice",
        key: key,
        security: jQuery("#gs-ajax-nonce").val(),
      },
      function () {
        showNextSlide(currentSlide);
      },
    );
  },
);

/**  */
document.addEventListener("DOMContentLoaded", function () {
  const slides = document.querySelectorAll(".notification-nf-slide");
  const prevBtn = document.querySelector(".notification-nf-slider-btn.prev");
  const nextBtn = document.querySelector(".notification-nf-slider-btn.next");

  let index = 0;

  function updateSlider() {
    if (!slides || slides.length === 0) return;
    slides.forEach((slide) => slide.classList.remove("active"));
    if (slides[index]) {
      slides[index].classList.add("active");
    }
  }

  if (nextBtn) {
    nextBtn.addEventListener("click", function () {
      index++;
      if (index >= slides.length) {
        index = 0;
      }
      updateSlider();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener("click", function () {
      index--;
      if (index < 0) {
        index = slides.length - 1;
      }
      updateSlider();
    });
  }

  if (slides.length) {
    updateSlider();
  }
});

/** Connection guide step slider (gsc-nav prev/next) */
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".gsc-slider-wrapper").forEach(function (wrapper) {
    const track = wrapper.querySelector(".gsc-slider");
    const slides = wrapper.querySelectorAll(".gsc-slide");
    const prevBtn = wrapper.querySelector(".gsc-nav.prev");
    const nextBtn = wrapper.querySelector(".gsc-nav.next");

    if (!track || !slides.length) return;

    let index = 0;

    function updateSlider() {
      track.style.transform = "translateX(-" + index * 100 + "%)";
    }

    if (nextBtn) {
      nextBtn.addEventListener("click", function () {
        index = (index + 1) % slides.length;
        updateSlider();
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener("click", function () {
        index = (index - 1 + slides.length) % slides.length;
        updateSlider();
      });
    }

    updateSlider();
  });
});

/** pro Sheets Integration  popup */
jQuery(document).ready(function ($) {
  $("#gs_ninja_dro_option").on("change", function () {
    const value = $(this).val();

    if (value === "1") {
      $("#nj-confirm-manual-popup-pro").removeClass("d-none");
    }
  });

  // Close popup (Cancel button)
  $(document).on("click", ".nj-popup-close-pro", function () {
    $("#nj-confirm-manual-popup-pro").addClass("d-none");
    $("#gs_ninja_dro_option").val("0");
  });

  // Close popup on overlay click
  $(document).on("click", "#nj-confirm-manual-popup-pro", function (e) {
    if ($(e.target).is(this)) {
      $(this).addClass("d-none");
      $("#gs_ninja_dro_option").val("0");
    }
  });
});

jQuery(document).ready(function ($) {
  $("#gs_ninja_dro_option").on("change", function () {
    const value = $(this).val();

    if (value === "2") {
      $("#nj-confirm-service-popup-pro").removeClass("d-none");
    }
  });

  // Close popup (Cancel button)
  $(document).on("click", ".nj-popup-service-close-pro", function () {
    $("#nj-confirm-service-popup-pro").addClass("d-none");
    $("#gs_ninja_dro_option").val("0");
  });

  // Close popup on overlay click
  $(document).on("click", "#nj-confirm-service-popup-pro", function (e) {
    if ($(e.target).is(this)) {
      $(this).addClass("d-none");
      $("#gs_ninja_dro_option").val("0");
    }
  });
});

/** when the return auth with code will scroll down to token save button */
jQuery(document).ready(function ($) {
  /* Check if URL has "code" parameter */
  const code = new URLSearchParams(window.location.search).get("code");

  if (!code) return;

  /*possible targets */
  const selectors = ["#save-njform-gs-code"];

  let target = null;

  /* find which ID exists  */
  for (let sel of selectors) {
    if (document.querySelector(sel)) {
      target = sel;
      break;
    }
  }

  if (target) {
    window.location.hash = target.replace("#", "");

    document.querySelector(target).scrollIntoView({
      behavior: "smooth",
      block: "start",
    });
  }
});

// Open popup instead of alert
jQuery(document).on("click", "#nj-deactivate-log-confirmation", function () {
  jQuery("#nj-confirm-deactive-popup-free").removeClass("d-none");
});

// Cancel button
jQuery(document).on("click", "#nj-deactive-popup-free-cancel", function () {
  jQuery("#nj-confirm-deactive-popup-free").addClass("d-none");
});

/** Clear Debug Log in integration tab */
jQuery(document).on("click", "#gsnj-clear-logs-btn", function (e) {
  jQuery(".gsnj-copy-logs-loading").addClass("loading");
  e.preventDefault();
  var $btn = jQuery(this);
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: {
      action: "nj_clear_error_logs",
      nonce: jQuery("#gs-ajax-nonce").val(),
    },
    success: function (response) {
      if (response && response.success) {
        jQuery(".gsc-copy-msg")
          .html("Error Logs cleared")
          .removeClass("d-none");

        setTimeout(function () {
          jQuery(".gsc-copy-msg").addClass("d-none");
        }, 2000);
        jQuery(".gsnj-copy-logs-loading").removeClass("loading");
        location.reload();
      }
    },
  });
});

/** For Copy Dubug Log */
document.addEventListener("DOMContentLoaded", function () {
  const copyBtn = document.getElementById("gsnj-copy-logs");
  const msgDiv = document.querySelector(".gsc-copy-msg");

  if (!copyBtn || !msgDiv) return;

  copyBtn.addEventListener("click", function (e) {
    e.preventDefault();

    const tbody = document.querySelector(".gsnj-error-log-table tbody");
    if (!tbody) return;

    const rows = tbody.querySelectorAll("tr");
    if (!rows.length) {
      showMessage("No logs found to copy.", "error");
      return;
    }

    let output = "";

    rows.forEach((tr) => {
      const cols = tr.querySelectorAll("td");
      if (cols.length < 5) return;

      const date = (cols[0].innerText || "").trim();
      const errorId = (cols[1].innerText || "").trim();
      const code = (cols[2].innerText || "").trim();
      const message = (cols[3].innerText || "").trim();

      /* Proper details extraction */
      const detailsCell = cols[4];
      const details = detailsCell.querySelector("pre")
        ? detailsCell.querySelector("pre").innerText.trim()
        : (detailsCell.innerText || "").trim();

      output += `Date: ${date}\n`;
      output += `Error ID: ${errorId}\n`;
      output += `Code: ${code}\n`;
      output += `Message: ${message}\n`;
      output += `Details: ${details}\n`;
      output += `\n==========================\n\n`;
    });

    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard
        .writeText(output)
        .then(function () {
          showMessage("Copied successfully", "success");
        })
        .catch(function () {
          fallbackCopy(output);
        });
    } else {
      fallbackCopy(output);
    }

    function fallbackCopy(text) {
      const ta = document.createElement("textarea");
      ta.value = text;
      document.body.appendChild(ta);
      ta.select();
      try {
        document.execCommand("copy");
        showMessage("Copied successfully", "success");
      } catch (err) {
        showMessage("Copy failed. Please copy manually.", "error");
      }
      document.body.removeChild(ta);
    }

    function showMessage(text, type) {
      msgDiv.innerText = text;

      msgDiv.classList.remove("d-none");
      setTimeout(function () {
        msgDiv.classList.add("d-none");
      }, 2000);
    }
  });
});

/**jQuery for save uninstall settings */
jQuery(document).ready(function ($) {
  const $checkbox = $("#gs_nf_uninstall_settings");
  const $saveBtn = $(".gs_nf_save_uninstall_settings");
  const $msg = $("#nf-uninstall-msg-free");
  const $loader = $(".loading-uninstall-free");
  const $popup = $("#nf-confirm-uninstall-data-popup-free");

  // Page load → disable button
  $saveBtn.prop("disabled", true).addClass("common-disable");

  // Checkbox change
  $checkbox.on("change", function () {
    // If user tries to enable it → show popup
    if ($(this).is(":checked")) {
      // Uncheck temporarily
      $(this).prop("checked", false);

      // Show popup
      $popup.removeClass("d-none");

      return;
    }

    // Enable save button normally when unchecked
    $saveBtn.prop("disabled", false).removeClass("common-disable");
  });

  /* =========================
     POPUP CONFIRM BUTTON
     ========================= */

  $("#nf-confirm-enable-uninstall-free").on("click", function () {
    $checkbox.prop("checked", true);

    $popup.addClass("d-none");

    $saveBtn.prop("disabled", false).removeClass("common-disable");
  });

  /* =========================
     POPUP CANCEL BUTTON
     ========================= */

  $("#nf-cancel-uninstall-free").on("click", function () {
    $checkbox.prop("checked", false);

    $popup.addClass("d-none");
  });

  /* =========================
     SAVE SETTINGS
     ========================= */

  $saveBtn.on("click", function (e) {
    e.preventDefault();

    var isChecked = $checkbox.is(":checked");

    $.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: "nf_save_uninstall_settings",
        uninstall_setting: isChecked ? 1 : 0,
        security: $("#gs-ajax-nonce").val(),
      },

      beforeSend: function () {
        $loader.addClass("loading");
        $saveBtn.prop("disabled", true).addClass("common-disable");
      },

      success: function (response) {
        if (!response.success) return;

        $msg.removeClass("gsc-success gsc-error d-none");

        $msg
          .addClass("gsc-success")
          .text("Plugin preferences updated successfully.");

        setTimeout(function () {
          $msg.addClass("d-none").text("");
        }, 2000);
      },

      error: function () {
        $msg
          .removeClass("d-none")
          .addClass("gsc-error")
          .text("Something went wrong");

        $saveBtn.prop("disabled", false).removeClass("common-disable");
      },

      complete: function () {
        $loader.removeClass("loading");
      },
    });
  });
});

// Bind toggle buttons
jQuery(document).ready(function ($) {
  $("#nf-info-container").show();
  function accordionToggle(button, container) {
    $(button).on("click", function () {
      if ($(container).is(":visible")) {
        // second click → close same section
        $(container).slideUp();
      } else {
        // open clicked, close others
        $(".info-content").slideUp();
        $(container).slideDown();
      }
    });
  }

  accordionToggle("#nf-show-info-button", "#nf-info-container");
  accordionToggle(
    "#nf-show-wordpress-info-button",
    "#nf-wordpress-info-container",
  );
  accordionToggle("#nf-show-active-info-button", "#nf-active-info-container");
  accordionToggle("#nf-show-netplug-info-button", "#nf-netplug-info-container");
  accordionToggle("#nf-show-acplug-info-button", "#nf-acplug-info-container");
  accordionToggle("#nf-show-server-info-button", "#nf-server-info-container");
  accordionToggle(
    "#nf-show-database-info-button",
    "#nf-database-info-container",
  );
  accordionToggle("#nf-show-wrcons-info-button", "#nf-wrcons-info-container");
  accordionToggle("#nf-show-ftps-info-button", "#nf-ftps-info-container");

  /*  Copy system info */
  $(document).on("click", "#nf-copy-system-info", function () {
    var systemInfoContainer = document.querySelector(".nf-free-info-container");
    if (!systemInfoContainer) return;

    var textToCopy = "";

    systemInfoContainer
      .querySelectorAll(".info-button")
      .forEach(function (btn) {
        var containerId = btn.id
          .replace("show-", "")
          .replace("-button", "-container");
        var container = document.getElementById(containerId);

        var heading = btn.innerText.trim();
        textToCopy += "\n====================================\n";
        textToCopy += heading + "\n";
        textToCopy += "====================================\n";

        if (container) {
          container.querySelectorAll("tr").forEach(function (row) {
            var cells = row.querySelectorAll("td");
            if (cells.length >= 2) {
              var rowText =
                cells[0].innerText.trim() + ": " + cells[1].innerText.trim();
              for (var i = 2; i < cells.length; i++) {
                rowText += " - " + cells[i].innerText.trim();
              }
              textToCopy += rowText + "\n";
            }
          });
        }

        textToCopy += "\n";
      });

    textToCopy = textToCopy.trim();

    if (!textToCopy) {
      console.error("Nothing to copy");
      return;
    }

    var btn = this;
    var msgDiv = btn.parentNode.querySelector(".gsc-copy-msg-info");
    if (!msgDiv) {
      msgDiv = document.createElement("div");
      msgDiv.className = "gsc-copy-msg";
      msgDiv.style.display = "none";
      btn.parentNode.appendChild(msgDiv);
    }

    function showCopied() {
      msgDiv.innerHTML = "Copied successfully";
      msgDiv.style.display = "block";
      setTimeout(function () {
        msgDiv.style.display = "none";
      }, 1000);
    }

    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard
        .writeText(textToCopy)
        .then(showCopied)
        .catch(function (err) {
          console.error("Clipboard error:", err);
        });
    } else {
      var textarea = document.createElement("textarea");
      textarea.value = textToCopy;
      textarea.style.position = "fixed";
      textarea.style.left = "-9999px";
      document.body.appendChild(textarea);
      textarea.focus();
      textarea.select();
      try {
        document.execCommand("copy");
        showCopied();
      } catch (e) {
        console.error("Copy failed", e);
      }
      document.body.removeChild(textarea);
    }
  });
});

/** Clear debug for system status tab */
jQuery(document).on("click", ".nf-clear-debug-logs", function () {
  jQuery(".loading-sign-setting").addClass("loading");
  var data = {
    action: "nf_clear_debug_logs",
    security: jQuery("#gs-ajax-nonce").val(),
  };
  jQuery.post(ajaxurl, data, function (response) {
    if (response == -1) {
      return false; /*  Invalid nonce */
    }

    if (response.success) {
      jQuery(".loading-sign-setting").removeClass("loading");

      /*  Show success message */
      var $msg = jQuery(".gsc-copy-msg");

      $msg.text("Logs are cleared").removeClass("d-none");

      setTimeout(function () {
        $msg.addClass("d-none");
      }, 3000);

      setTimeout(function () {
        location.reload();
      }, 1000);
    }
  });
});

/** download debug log csv file */
jQuery(document).ready(function ($) {
  $("#nf-download-csv").on("click", function (e) {
    e.preventDefault();
    var rows = $("table.widefat tr");
    var csvContent = "";

    if (rows.length === 0) {
      return;
    }
    rows.each(function () {
      var cols = $(this).find("th, td");
      var rowData = [];

      cols.each(function () {
        var text = $(this).text().trim();

        /*  Escape quotes */
        text = text.replace(/"/g, '""');

        rowData.push('"' + text + '"');
      });

      csvContent += rowData.join(",") + "\n";
    });

    /*  Create Blob */
    var blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });

    var link = document.createElement("a");
    var url = URL.createObjectURL(blob);

    link.setAttribute("href", url);
    link.setAttribute("download", "debug-log.csv");
    link.style.visibility = "hidden";

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });
});

/** Copy debug log  */
jQuery(document).ready(function ($) {
  $("#nf-copy-logs").on("click", function (e) {
    e.preventDefault();

    var rows = $("table tbody tr");
    var copyText = "";

    if (!rows.length) {
      return;
    }

    rows.each(function () {
      var cols = $(this).find("td");

      if (cols.length >= 4) {
        copyText += $(cols[0]).text().trim() + "\n"; /* Date */
        copyText += $(cols[1]).text().trim() + "\n"; /* Type */
        copyText += $(cols[2]).text().trim() + "\n"; /* Message */
        copyText += $(cols[3]).text().trim() + "\n"; /* File */
        copyText += "----------------------------------------\n\n";
      }
    });

    /*  Temporary textarea copy */
    var tempTextarea = $("<textarea>");
    $("body").append(tempTextarea);
    tempTextarea.val(copyText).select();
    document.execCommand("copy");
    tempTextarea.remove();

    /*  Show success message */
    var $msg = $(".gsc-copy-msg");

    $msg.text("Copied successfully").removeClass("d-none");

    setTimeout(function () {
      $msg.addClass("d-none");
    }, 3000);
  });
});

/* redirect select form * */
jQuery(document).ready(function ($) {
  $("#ninja_select").on("change", function (e) {
    e.preventDefault();
    var formUrl = $(this).val();

    if (formUrl) {
      window.open(formUrl, "_blank");
    }
  });
});

/* Extenxion Counter */
jQuery(document).ready(function ($) {
  $(".nf-pro-counter").each(function () {
    let $this = $(this);
    let countTo = parseFloat($this.attr("data-count"));

    $({ countNum: 0 }).animate(
      {
        countNum: countTo,
      },
      {
        duration: 2500,
        easing: "swing",

        step: function () {
          if (countTo % 1 !== 0) {
            $this.text(this.countNum.toFixed(1));
          } else {
            $this.text(Math.floor(this.countNum));
          }
        },

        complete: function () {
          if (countTo % 1 !== 0) {
            $this.text(countTo.toFixed(1));
          } else {
            $this.text(countTo);
          }
        },
      },
    );
  });
});

/**
 * Handle plugin activation button click via AJAX.
 *
 * - Shows loading spinner
 * - Sends plugin slug to server for activation
 * - On success, updates button to "Activated" and reloads page
 * - On error or failure, resets button and removes loading state
 */

jQuery(document).on("click", ".nf-activate-plugin-btn", function () {
  var button = jQuery(this);
  var pluginSlug = button.data("plugin");
  var loaderSpan = button.siblings(".loading-sign-active");

  loaderSpan.addClass("loading");
  button.prop("disabled", true);

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    dataType: "json",
    data: {
      action: "nf_activate_plugin",
      plugin_slug: pluginSlug,
      security: jQuery("#gs-ajax-nonce").val(),
    },

    success: function (response) {
      loaderSpan.removeClass("loading");

      if (response.success) {
        location.reload();
      } else {
        jQuery(".popup-actions-active-msg").text(
          response.data.message ||
            "You do not have permission to activate this plugin.",
        );

        jQuery("#nf-confirm-active-popup").removeClass("d-none");

        button.prop("disabled", false);
      }
    },

    error: function () {
      loaderSpan.removeClass("loading");

      jQuery(".popup-actions-active-msg").text(
        "Something went wrong. Please try again.",
      );

      jQuery("#nf-confirm-active-popup").removeClass("d-none");

      button.prop("disabled", false);
    },
  });
});

/**
 * Handle plugin deactivation button click via AJAX.
 *
 * - Sends plugin slug to server for deactivation
 * - On success, shows alert and reloads the page
 * - On error, shows AJAX error alert
 */
jQuery(document).ready(function (jQuery) {
  let nfPluginSlug = null;

  // Open confirmation popup
  jQuery(document).on("click", ".nf-deactivate-plugin", function (e) {
    e.preventDefault();
    nfPluginSlug = jQuery(this).data("plugin");
    jQuery("#nf-confirm-dective-popup").removeClass("d-none");
  });

  // Cancel popup
  jQuery("#nf-dective-popup-cancel").on("click", function () {
    nfPluginSlug = null;
    jQuery("#nf-confirm-dective-popup").addClass("d-none");
  });

  // Confirm deactivate
  jQuery("#nf-deactive-popup-confirm").on("click", function () {
    if (!nfPluginSlug) return;
    var $btn = jQuery(".nf-deactivate-plugin");

    /*  Remove loading from ALL loaders first */
    jQuery(".loading-sign-deactive").removeClass("loading");

    /*  Add loading only to the current one */
    /*$btn.siblings(".loading-sign-deactive").first().addClass("loading");*/
    jQuery('.nf-deactivate-plugin[data-download="' + nfPluginSlug + '"]')
      .siblings(".loading-sign-deactive")
      .addClass("loading");

    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: "nf_deactivate_plugin",
        plugin_slug: nfPluginSlug,
        security: jQuery("#gs-ajax-nonce").val(),
      },
      success: function (response) {
        if (response.success) {
          jQuery(".success-message")
            .text(response.data || "Integration deactivated successfully!")
            .fadeIn()
            .delay(3000)
            .fadeOut();
          $btn.removeClass("loading");
          location.reload();
        }
      },
      error: function () {
        $btn.removeClass("loading");
      },
    });

    // Close popup
    jQuery("#nf-confirm-dective-popup").addClass("d-none");
    nfPluginSlug = null;
  });
  jQuery(document).on("click", "#nf-popup-active", function (e) {
    e.preventDefault();
    e.stopPropagation();

    jQuery("#nf-confirm-active-popup").addClass("d-none");
  });
});

/**
 * Handle plugin install button click via AJAX.
 *
 * - Shows loading spinner
 * - Sends plugin slug and download URL to server
 * - On success, hides install button and shows activate button
 * - On error or failure, resets button state
 */
jQuery(document).ready(function (jQuery) {
  jQuery(".nf-install-plugin-btn").on("click", function () {
    var button = jQuery(this);
    var pluginSlug = button.data("plugin");
    var downloadUrl = button.data("download");
    var loaderSpan = button
      .closest(".button-bar")
      .find(".loading-sign-install");

    loaderSpan.addClass("loading");
    button.prop("disabled", true);

    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: "nf_install_plugin",
        plugin_slug: pluginSlug,
        download_url: downloadUrl,
        security: jQuery("#gs-ajax-nonce").val(),
      },

      success: function (response) {
        loaderSpan.removeClass("loading");

        if (response.success) {
          button.hide();

          button.closest(".button-bar").find(".nf-activate-plugin-btn").show();
        } else {
          jQuery(".popup-actions-active-msg").text(
            response.data.message ||
              "You do not have permission to install this plugin.",
          );

          jQuery("#nf-confirm-active-popup").removeClass("d-none");

          button.prop("disabled", false);
        }
      },

      error: function () {
        loaderSpan.removeClass("loading");

        jQuery(".popup-actions-active-msg").text(
          "Something went wrong. Please try again.",
        );

        jQuery("#nf-confirm-active-popup").removeClass("d-none");

        button.prop("disabled", false);
      },
    });
  });
});

/***new slider for without permission for existing method */
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".gsc-slider-wrapper").forEach(function (wrapper) {
    const slider = wrapper.querySelector(".gsc-slider");
    const slides = wrapper.querySelectorAll(".gsc-slide");
    const prevBtn = wrapper.querySelector(".gsc-nav.prev");
    const nextBtn = wrapper.querySelector(".gsc-nav.next");
    let current = 0;
    function updateSlider() {
      slider.style.transform = "translateX(" + -current * 100 + "%)";
    }
    nextBtn?.addEventListener("click", function () {
      current = (current + 1) % slides.length;
      updateSlider();
    });
    prevBtn?.addEventListener("click", function () {
      current = (current - 1 + slides.length) % slides.length;
      updateSlider();
    });
    /*  attach function */
    wrapper.goToSlide = function (index) {
      current = index;
      updateSlider();
    };
  });

  /*  AUTO MOVE TO STEP 4   */
  setTimeout(function () {
    const errorBox = document.querySelector(".nj-permission-error");
    const target = document.querySelector(".nj-connection-guide-slider");
    if (!errorBox) {
      return;
    }
    if (!target) {
      return;
    }
    if (target.goToSlide) {
      target.goToSlide(3);
      target.scrollIntoView({
        behavior: "smooth",
        block: "center",
      });
    } else {
    }
  }, 800);
});

/** JS for display auth method */
document.addEventListener("DOMContentLoaded", function () {
  var el = document.querySelector(".nf-selected-method");

  if (el && el.dataset.value && el.dataset.value.trim() !== "") {
    var badgeText = el.dataset.value.trim();

    document
      .querySelectorAll(".nav-tab-wrapper .nav-tab")
      .forEach(function (tab) {
        var href = tab.getAttribute("href") || "";

        if (href.indexOf("tab=integration") !== -1) {
          tab.style.position = "relative";

          if (tab.querySelector(".nf-selected-badge")) return;
          var badge = document.createElement("div");

          if (badgeText == "Auth Required") {
            badge.className = "nf-auth-required-selected-badge"; // ✅ IMPORTANT
            badge.textContent = badgeText;
          } else {
            badge.className = "nf-selected-badge"; // ✅ IMPORTANT
            badge.textContent = badgeText;
          }
          tab.appendChild(badge);
        }
      });
  }
});

/** Custom select for auth method */
function initAutoSelect() {
  document
    .querySelectorAll("select#gs_ninja_dro_option")
    .forEach(function (select) {
      if (select.classList.contains("auto-processed")) return;
      select.classList.add("auto-processed");

      select.style.display = "none";

      const wrapper = document.createElement("div");
      wrapper.className = "auto-select";

      const display = document.createElement("div");
      display.className = "auto-select-display";

      const optionsBox = document.createElement("div");
      optionsBox.className = "auto-select-options";

      function setSelectedText() {
        const selectedOption = select.options[select.selectedIndex];
        display.innerText = selectedOption
          ? selectedOption.text.trim()
          : "Select";

        optionsBox
          .querySelectorAll(".auto-select-option")
          .forEach(function (el, i) {
            el.classList.toggle("selected", i === select.selectedIndex);
          });
      }

      Array.from(select.options).forEach(function (option, index) {
        const item = document.createElement("div");
        item.className = "auto-select-option";
        item.innerText = option.text.trim();

        item.addEventListener("click", function () {
          select.selectedIndex = index;
          optionsBox.style.display = "none";
          select.dispatchEvent(new Event("change", { bubbles: true }));
        });

        optionsBox.appendChild(item);
      });

      display.addEventListener("click", function (e) {
        e.stopPropagation();
        document
          .querySelectorAll(".auto-select-options")
          .forEach(function (box) {
            if (box !== optionsBox) box.style.display = "none";
          });
        optionsBox.style.display =
          optionsBox.style.display === "block" ? "none" : "block";
      });

      select.parentNode.insertBefore(wrapper, select);
      wrapper.appendChild(display);
      wrapper.appendChild(optionsBox);
      wrapper.appendChild(select);

      setSelectedText();
      select.addEventListener("change", setSelectedText);
    });
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initAutoSelect);
} else {
  initAutoSelect();
}

/* close dropdown on outside click */
document.addEventListener("click", function (e) {
  document.querySelectorAll(".auto-select-options").forEach(function (box) {
    if (!box.parentElement.contains(e.target)) {
      box.style.display = "none";
    }
  });
});

document.querySelectorAll(".gscnff-market-tab").forEach((tab) => {
  tab.addEventListener("click", function () {
    let filter = this.dataset.filter;

    document
      .querySelectorAll(".gscnff-market-tab")
      .forEach((t) => t.classList.remove("active"));

    this.classList.add("active");

    document.querySelectorAll(".gsc-market-item").forEach((card) => {
      if (filter === "all") {
        card.style.display = "block";
      } else {
        card.style.display = card.classList.contains(filter) ? "block" : "none";
      }
    });
  });
});


jQuery(document).ready(function(){
	jQuery(".gsheetconnector-addons-list").each(function () {
    if (jQuery(this).html().trim().length === 0) {
      jQuery(this).addClass("blank_div");
      jQuery(this).prev("div").hide();
    }
  });
});