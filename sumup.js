var sumUpConfig = null;

function SaveSumUpConfig(config, button = '', reload = false, success_msg = '') {
  // var data = JSON.stringify(config);
  $.ajax({
    type: "POST",
    // url: 'api/configfile/plugin.fpp-zettle.json',
    url: 'plugin.php?plugin=fpp-sumup&page=sumup.php&command=update_json&nopage=1',
    async: false,
    data: config,
    dataType: 'json',
    async: false,
    beforeSend: function () {
      if (button != '') {
        $(button).prop('disabled', true);
      }
    },
    success: function (data) {
      $.jGrowl(success_msg, {
        themeState: 'success'
      });
      if (reload) {
        ;
        setTimeout(function () {
          location.reload();
        }, 3000);
      }
    },
    error: function () {
      if (button != '') {
        $(button).prop('disabled', false);
      }
      DialogError('Error', "ERROR: There was an error, please try again!");
    }
  });
}

function UpgradePlugin() {
  var url = 'api/plugin/fpp-sumup/upgrade?stream=true';

  $('#pluginsProgressPopup').fppDialog({
    width: 900,
    title: "Upgrade Plugin",
    dialogClass: 'no-close'
  });
  $('#pluginsProgressPopup').fppDialog("moveToTop");
  document.getElementById('pluginsText').value = '';
  StreamURL(url, 'pluginsText', 'PluginProgressDialogDone', 'PluginProgressDialogDone');
}

$(function () {
  allowMultisyncCommands = true;

  if ($('#pluginupdate').length) {
    $.ajax({
      type: "POST",
      url: 'api/plugin/fpp-sumup/updates',
      dataType: 'json',
      contentType: 'application/json',
      success: function (data) {
        if (data.updatesAvailable) {
          $('#pluginupdate').show();
        }
      }
    });
  }

  $.get('api/configfile/plugin.fpp-sumup.json')
    .done(function (data) {
      processSumUpConfig(data);
    })
    .fail(function (data) {
      processSumUpConfig('[]');
    });

  function processSumUpConfig(config) {
    if (typeof config === "string") {
      sumUpConfig = $.parseJSON(config);
    } else {
      sumUpConfig = config;
    }

    if ($('#api_effect').length > 0) {
      console.log('api_effect found');
      var newButtonRowCommand = 'button_TPL_Command';
      var newButtonRowTable = 'tableButtonTPL';

      LoadCommandList(newButtonRowCommand);
      PopulateExistingCommand(sumUpConfig, newButtonRowCommand, newButtonRowTable, true);

      if (
        sumUpConfig.command == 'Overlay Model Effect' && sumUpConfig.args[2] == 'Text' ||
        sumUpConfig.command == 'URL'
      ) {
        $('#text_options').show();
      }

      $('.buttonCommand').attr('id', newButtonRowCommand).on('change', function () {
        CommandSelectChanged(newButtonRowCommand, newButtonRowTable, true);
        if ($("#" + newButtonRowCommand).val() == 'URL') {
          $('#text_options').fadeIn();
        } else {
          $('#text_options').fadeOut();
        }

        $('#tableButtonTPL_arg_3_row').find('select').on('change', function () {
          if ($(this).val() == 'Text') {
            $('#text_options').fadeIn();
          } else {
            $('#text_options').fadeOut();
          }
        });
      });
    }
  }

  $('#api_effect').on('submit', function (e) {
    e.preventDefault();

    var effect = $('#select_effect option:selected').val();

    $('[id^="tableButton"]').each(function () {
      var oldId = $(this).prop('id')
      var idArr = oldId.split('_');
      idArr[0] = 'tableButtonTPL'
      $(this).attr('id', idArr.join('_'));
      console.log($(this).attr('id', idArr.join('_')));
    });

    var sumUp = {
      "option": 'effect',
      "effect_activate": $('#effect_activate option:selected').val(),
    };
    CommandToJSON('button_TPL_Command', 'tableButtonTPL', sumUp);
    SaveSumUpConfig(sumUp, '#effect_save', true, 'Effect Saved!');
  });

  // Test command with out the need to save it first
  $('#test_command').on('click', function () {
    // Check for command
    if (sumUpConfig.command == '') {
      // Display error to user if command not found
      DialogError('Error', 'No command found, please select a command!');
    } else {
      var url = "api/command/";
      var data = {};
      // Get command data
      CommandToJSON('button_TPL_Command', 'tableButtonTPL', data);
      // Build url with selected command
      url += data['command'];
      // Send ajax to test command to see if user likes it before they save it
      $.ajax({
        type: "POST",
        url: url,
        dataType: 'text',
        async: false,
        data: JSON.stringify(data['args']),
        processData: false,
        contentType: 'application/json',
        success: function (data) {
          if (data != '') {
            $.jGrowl('Test Sent!', {
              themeState: 'success'
            });
            $.jGrowl('If you like what you see don\'t forget to save it!!', {
              themeState: 'success',
              life: 5000
            });
          }
        }
      });
    }
  });

  $('#pushover').on('submit', function (e) {
    e.preventDefault();

    var thisForm = $(this);
    var submitButton = $("input[type=submit]", thisForm);

    $.ajax({
      type: "POST",
      url: "plugin.php?plugin=fpp-sumup&page=sumup.php&command=save_pushover&nopage=1",
      dataType: 'json',
      async: false,
      data: {
        option: "pusher",
        activate: $('#pushover_activate option:selected').val(),
        app_token: $('#pushover_app_token').val(),
        user_key: $('#pushover_user_key').val(),
        message: $('#pushover_message').val()
      },
      beforeSend: function () {
        $(submitButton).prop('disabled', true);
      },
      success: function (data) {
        $.jGrowl(data.message, {
          themeState: "success"
        });
        setTimeout(function () {
          location.reload();
        }, 3000);
      }
    });
  });
});
