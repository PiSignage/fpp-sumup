<script type="text/javascript" src="/plugin.php?plugin=fpp-sumup&file=sumup.js&nopage=1"></script>
<div id="global" class="settings">
  <legend>Effect</legend>
  <p>Select a command that you would like to run when a transaction comes in</p>
  <form id="api_effect" action="" method="post">
    <div class="container-fluid settingsTable settingsGroupTable">
      <div class="row">
        <div class="buttonCommandWrap">
          <select id='button_TPL_Command' class="buttonCommand" required>
            <option value="" disabled selected>Select a Command</option>
          </select>
          <div class="bb_commandTableWrap">
            <div class="bb_commandTableCrop">
              <table border="0" id="tableButtonTPL" class="tableButton"></table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <input id="effect_save" type="submit" value="Save" class="buttons btn-success">
    <input id="test_command" type="button" value="Test" class="buttons">
  </form>
</div>
