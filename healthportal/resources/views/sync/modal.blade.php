<div class="ui small modal sync">
  <i class="close icon"></i>
  <div class="header">
    Sync Data
  </div>
  <div class="content">
    Client Records <span class="checkmark-space cr"></span>
    <div class='ui divider'></div>
    Inventory <span class="checkmark-space in"></span>
    <div class='ui divider'></div>
    Services <span class="checkmark-space se"></span>
    <div class='ui divider'></div>
    <div class="ui teal progress" id="progressbar">
      <div class="bar"></div>
      <div class="label">Ready to Sync</div>
    </div>
  </div>
  <div class="actions">
    Last Sync: 11/11/2016
    <div class="ui button primary" id='sync-button'>Sync</div>
    <div class="ui button" id='cancel-button'>Cancel</div>
  </div>
</div>

<script src='/js/sync.js'></script>
