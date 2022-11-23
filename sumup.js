var sumupConfig = null;

$(function () {
  allowMultisyncCommands = true;
  $.ajax({
    type: "GET",
    url: 'fppjson.php?command=getPluginJSON&plugin=fpp-sumup',
    dataType: 'json',
    contentType: 'application/json',
    success: function (data) {
      if (typeof data === "string") {
        sumupConfig = $.parseJSON(data);
      } else {
        sumupConfig = data;
      }
      if ($('#api_effect').length > 0) {
        console.log('api_effect found');
        var newButtonRowCommand = 'button_TPL_Command';
        var newButtonRowTable = 'tableButtonTPL';

        LoadCommandList(newButtonRowCommand);
        PopulateExistingCommand(sumupConfig, newButtonRowCommand, newButtonRowTable, true);

        if (sumupConfig.command == 'Overlay Model Effect' && sumupConfig.args[2] == 'Text') {
          $('#text_options').show();
        }

        $('.buttonCommand').attr('id', newButtonRowCommand).on('change', function () {
          CommandSelectChanged(newButtonRowCommand, newButtonRowTable, true);

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
  });

  // Test command with out the need to save it first
  $('#test_command').on('click', function () {
    // Check for command
    if (sumupConfig.command == '') {
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
});
