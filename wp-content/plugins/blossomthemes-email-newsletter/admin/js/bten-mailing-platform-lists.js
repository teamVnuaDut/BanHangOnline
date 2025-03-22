jQuery(document).ready(function () {
  //MailChimp Lists
  jQuery("body").on("click", ".bten_get_mailchimp_lists", function (e) {
    ListsSelect = jQuery("#" + jQuery(this).attr("rel-id"));
    ListsSelect.find("option").remove();
    var nonce = jQuery(this).data("nonce");
    jQuery("<option/>").val(0).text("Loading...").appendTo(ListsSelect);
    jQuery.ajax({
      url: ajaxurl,
      data: {
        action: "bten_get_mailing_list",
        calling_action: "bten_mailchimp_list",
        bten_mc_api_key: jQuery("#bten_mailchimp_api_key").val(),
        nonce: nonce
      },
      dataType: "JSON",
      type: "POST",
      success: function (response) {
        ListsSelect.find("option").remove();
        jQuery.each(response, function (i, option) {
          if (option.name) {
            jQuery("<option/>")
              .val(option.id)
              .text(option.name)
              .appendTo(ListsSelect);
          } else {
            jQuery("<option/>")
              .val(i)
              .text("No Lists Found")
              .appendTo(ListsSelect);
          }
        });
      },
      error: function (errorThrown) {
        alert("Error...");
      },
    });
  });

  //MailerLite Lists
  jQuery("body").on("click", ".bten_get_mailerlite_lists", function (e) {
    ListsSelect = jQuery("#" + jQuery(this).attr("rel-id"));
    ListsSelect.find("option").remove();
    var nonce = jQuery(this).data("nonce");
    jQuery("<option/>").val(0).text("Loading...").appendTo(ListsSelect);
    jQuery.ajax({
      url: ajaxurl,
      data: {
        action: "bten_get_mailing_list",
        calling_action: "bten_mailerlite_list",
        bten_ml_api_key: jQuery("#bten_mailerlite_api_key").val(),
        nonce: nonce
      },
      dataType: "JSON",
      type: "POST",
      success: function (response) {
        ListsSelect.find("option").remove();
        jQuery.each(response.data, function (i, option) {
          jQuery("<option/>")
            .val(option.id)
            .text(option.name)
            .appendTo(ListsSelect);
        });
      },
      error: function (errorThrown) {
        ListsSelect.find("option").remove();
        jQuery("<option/>")
          .val("-")
          .text("No Lists Found")
          .appendTo(ListsSelect);
        alert("Error: Invalid API key");
      },
    });
  });

  //ConvertKit Lists
  jQuery("body").on("click", ".bten_get_convertkit_lists", function (e) {
    ListsSelect = jQuery("#" + jQuery(this).attr("rel-id"));
    ListsSelect.find("option").remove();
    var nonce = jQuery(this).data("nonce");
    jQuery("<option/>").val(0).text("Loading...").appendTo(ListsSelect);
    jQuery.ajax({
      url: ajaxurl,
      data: {
        action: "bten_get_mailing_list",
        calling_action: "bten_convertkit_list",
        bten_ck_api_key: jQuery("#bten_convertkit_api_key").val(),
        nonce: nonce
      },
      dataType: "JSON",
      type: "POST",
      success: function (response) {
        ListsSelect.find("option").remove();
        jQuery.each(response, function (i, option) {
          jQuery("<option/>").val(i).text(option.name).appendTo(ListsSelect);
        });
      },
      error: function (errorThrown) {
        alert("Error...");
      },
    });
  });

  //GetResponse Lists
  jQuery("body").on("click", ".bten_get_getresponse_lists", function (e) {
    ListsSelect = jQuery("#" + jQuery(this).attr("rel-id"));
    ListsSelect.find("option").remove();
    var nonce = jQuery(this).data("nonce");
    jQuery("<option/>").val(0).text("Loading...").appendTo(ListsSelect);
    jQuery.ajax({
      url: ajaxurl,
      data: {
        action: "bten_get_mailing_list",
        calling_action: "bten_getresponse_list",
        bten_gr_api_key: jQuery("#bten_getresponse_api_key").val(),
        nonce: nonce
      },
      dataType: "JSON",
      type: "POST",
      success: function (response) {
        ListsSelect.find("option").remove();
        jQuery.each(response, function (i, option) {
          jQuery("<option/>")
            .val(option.campaignId)
            .text(option.name)
            .appendTo(ListsSelect);
        });
      },
      error: function (errorThrown) {
        ListsSelect.find("option").remove();
        jQuery("<option/>")
          .val("-")
          .text("No Lists Found")
          .appendTo(ListsSelect);
        alert("Error: Invalid API key");
      },
    });
  });

  //sendinblue Lists
  jQuery("body").on("click", ".bten_get_sendinblue_lists", function (e) {
    if ("" === jQuery("#bten_sendinblue_api_key").val()) {
      alert("Please enter your API key first");
      return false;
    }

    ListsSelect = jQuery("#" + jQuery(this).attr("rel-id"));
    ListsSelect.find("option").remove();
    var nonce = jQuery(this).data("nonce");
    jQuery("<option/>").val(0).text("Loading...").appendTo(ListsSelect);
    jQuery.ajax({
      url: ajaxurl,
      data: {
        action: "bten_get_mailing_list",
        calling_action: "bten_sendinblue_list",
        bten_sendin_api_key: jQuery("#bten_sendinblue_api_key").val(),
        nonce: nonce
      },
      dataType: "JSON",
      type: "POST",
      success: function (response) {
        ListsSelect.find("option").remove();
        jQuery.each(response.data, function (i, option) {
          jQuery("<option/>")
            .val(option.id)
            .text(option.name)
            .appendTo(ListsSelect);
        });
      },
      error: function (errorThrown) {
        ListsSelect.find("option").remove();
        jQuery("<option/>")
          .val("-")
          .text("No Lists Found")
          .appendTo(ListsSelect);
        alert("Error: Invalid API key");
      },
    });
  });

  //ActiveCampaign Lists
  jQuery("body").on("click", ".bten_get_activecampaign_lists", function (e) {
    var ListsSelect = jQuery("#" + jQuery(this).attr("rel-id"));
    ListsSelect.find("option").remove();
    var nonce = jQuery(this).data("nonce");
    var settingsErrorDiv = jQuery("#setting-error-settings_updated");

    jQuery("<option/>").val(0).text("Loading...").appendTo(ListsSelect);

    jQuery.ajax({
      url: ajaxurl,
      data: {
        action: "bten_get_mailing_list",
        calling_action: "bten_activecampaign_list",
        bten_ac_api_url: jQuery("#bten_activecampaign_api_url").val(),
        bten_ac_api_key: jQuery("#bten_activecampaign_api_key").val(),
        nonce: nonce
      },
      dataType: "JSON",
      type: "POST",
      success: function (response) {
        settingsErrorDiv.remove();
        ListsSelect.empty();

        jQuery.each(response, function (i, option) {
          jQuery("<option/>").val(i).text(option.name).appendTo(ListsSelect);
        });
      },
      error: function (errorThrown) {
        var message = errorThrown?.responseText?.replace(/0$/, '');
        var errorMessage = message ? JSON.parse(message)?.errorMessage : null;

        if (errorMessage) {
          settingsErrorDiv.remove();
          jQuery(".btnb-header").after('<div id="setting-error-settings_updated" class="error settings-error notice is-dismissible"><p><strong>' + errorMessage + '</strong></p></div>');
        } else {
          ListsSelect.empty().append(jQuery("<option/>").val("-").text("No Lists Found"));
          alert("Error: Invalid API key or Url");
        }
      }
    });
  });
});
