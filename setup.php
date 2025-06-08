<?php
include_once "sumup.common.php";
$pluginName = "sumup";
$pluginJson = convertAndGetSettingsSumUp($pluginName);
?>
<link rel="stylesheet" href="/plugin.php?plugin=fpp-sumup&file=sumup.css&nopage=1">
<script type="text/javascript" src="/plugin.php?plugin=fpp-sumup&file=sumup.js&nopage=1"></script>
<div id="global" class="settings">
  <?php include $settings["pluginDirectory"] . "/fpp-sumup/pluginUpdate.php" ?>

  <div class="alert alert-info">
    This plugin works with custom Sumup Donation andriod app. It can be downloaded <a
      href="https://fpp-zettle.s3.dualstack.eu-west-2.amazonaws.com/sumup_donation_app.apk" target="_blank">here</a>
    <br>
    Note this app is not publised on the andriod play store and will need to have Install Unknown Apps enabled on your
    andriod derive.
  </div>

  <legend>Effect</legend>
  <p>Select a command that you would like to run when a transaction comes in</p>

  <div id="text_options" class="callout callout-info" style="display: none;">
    <h4>Overlay Model Effect and URL Text Options</h4>
    <p>There are a number of options available.</p>
    <ul>
      <li>{{AMOUNT}} : Show the amount the person donated</li>
    </ul>
    <p>Note: You can put what ever you want in the text felid does not to have the above options in it.</p>
  </div>

  <form id="api_effect" action="" method="post">
    <div class="container-fluid settingsTable settingsGroupTable">
      <div class="row">
        <div class="buttonCommandWrap">
          <div class="bb_commandTableWrap">
            <div class="bb_commandTableCrop">
              <table border=0 id="tableButtonTPL" class="tableButton">
                <tr>
                  <td>Activate:</td>
                  <td>
                    <select id="effect_activate" required>
                      <option value="yes" <?php echo $pluginJson['effect_activate'] == 'yes' ? 'selected' : null; ?>>Yes
                      </option>
                      <option value="no" <?php echo $pluginJson['effect_activate'] == 'no' ? 'selected' : null;
                      echo !isset($pluginJson['effect_activate']) ? 'selected' : ''; ?>>No</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Command:</td>
                  <td>
                    <select id="button_TPL_Command" class="buttonCommand" required>
                      <option value="" disabled selected>Select a Command</option>
                    </select>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <input id="effect_save" type="submit" value="Save" class="buttons btn-success">
    <input id="test_command" type="button" value="Test" class="buttons">
  </form>

  <div class="alert alert-info">If you are looking for help press F1 or <a
      href="plugin.php?plugin=fpp-sumup&page=help.php" class="alert-link" target="_blank">Click Here</a></div>

  <legend>Pushover</legend>
  <p>Get notification sent your phone every time a donate is made. Pushover is free to use for 30 days. If you want to
    use it for longer there is a $5 USD one-time purchase fee. Check out the details at there website: <a
      href="https://pushover.net/" target="_blank">https://pushover.net</a></p>

  <form id="pushover" action="">
    <div class="container-fluid settingsTable settingsGroupTable">
      <div class="row">
        <div class="printSettingLabelCol col-md-4 col-lg-3 col-xxxl-2">
          <div class="description">
            <i class="fas fa-fw fa-nbsp ui-level-0"></i>Activate
          </div>
        </div>
        <div class="printSettingFieldCol col-md">
          <select id="pushover_activate" required class="form-control">
            <option value="yes" <?php echo $pluginJson['pushover']['activate'] == 'yes' ? 'selected' : null; ?>>Yes
            </option>
            <option value="no" <?php echo $pluginJson['pushover']['activate'] == 'no' ? 'selected' : null;
            echo !isset($pluginJson['pushover']['activate']) ? 'selected' : ''; ?>>No</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="printSettingLabelCol col-md-4 col-lg-3 col-xxxl-2">
          <div class="description">
            <i class="fas fa-fw fa-nbsp ui-level-0"></i>Application API Token
          </div>
        </div>
        <div class="printSettingFieldCol col-md">
          <input type="text" id="pushover_app_token" value="<?php echo $pluginJson["pushover"]["app_token"]; ?>"
            required class="form-control">
        </div>
      </div>
      <div class="row">
        <div class="printSettingLabelCol col-md-4 col-lg-3 col-xxxl-2">
          <div class="description">
            <i class="fas fa-fw fa-nbsp ui-level-0"></i>User Key
          </div>
        </div>
        <div class="printSettingFieldCol col-md">
          <input type="text" id="pushover_user_key" value="<?php echo $pluginJson["pushover"]["user_key"]; ?>" required
            class="form-control">
        </div>
      </div>
      <div class="row">
        <div class="printSettingLabelCol col-md-4 col-lg-3 col-xxxl-2">
          <div class="description">
            <i class="fas fa-fw fa-nbsp ui-level-0"></i>Message
          </div>
        </div>
        <div class="printSettingFieldCol col-md">
          <input type="text" id="pushover_message" value="<?php echo $pluginJson["pushover"]["message"]; ?>" required
            class="form-control">
        </div>
      </div>
    </div>
    <input type="submit" value="Save" class="buttons btn-success">
  </form>
</div>