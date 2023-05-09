<div id="st-trigger-effects" class="column">
  <button class="hamburger" type="button" data-effect="st-effect-1" title="Open the sidebar">
          <span class="hamburger-box">
            <span class="hamburger-inner"></span>
          </span>
        </button>


  <div id="gantt-header-toolbar" style="">

                              <input type="text" id="gantt_search_text" class="form-control" style="" placeholder="Search">
 
    <button class="header-toolbar-button undo undo-ui"><img class="header-button-icon" src="img/svg/undo.svg">&nbsp;Undo&nbsp;</button>
    <button class="header-toolbar-button redo redo-ui"><img class="header-button-icon" src="img/svg/redo.svg">&nbsp;Redo&nbsp;</button>

    <div class="nav-item nav-item-main dropdown">
      <a class="" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <button class="header-toolbar-button" id="toolbar-zoom-options"><img class="header-button-icon" src="img/svg/zoom.svg">&nbsp;Zoom&nbsp;</button>
                            </a>
      <span class="dropdown-menu dropdown-secondary" id="zoom-dropdown-options" aria-labelledby="zoom-dropdown">
      <a class="" id="btn-zoom-hours" title="Set the zoom level to hours "><span class="small">Zoom to hours</span></a>
      <a class="" id="btn-zoom-days" title="Set the zoom level to days "><span class="small">Zoom to days</span></a>
      <a class="" id="btn-zoom-weeks" title="Set the zoom level to weeks"><span class="small">Zoom to weeks</span></a>
      <a class="" id="btn-zoom-quarters" title="Set the zoom level to quarters"><span class="small">Zoom to quarters</span></a>
      <a class="" data-toggle="modal" id="modal_date_range" title="Show a date range" data-target="#modal_date_range"><span class="small">Change the date range</span></a>
      </span>
    </div>























    <div class="nav-item nav-item-main dropdown">
      <a class="" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <button class="header-toolbar-button" id="toolbar-view-options"><img class="header-button-icon" src="img/svg/options.svg">&nbsp;Options&nbsp;</button>
                            </a>
      <span class="dropdown-menu dropdown-secondary view-dropdown-options" aria-labelledby="view-dropdown">
      <a class="edit-task-columns" id="show-gantt-columns" title="Show/hide Gantt columns"><span class="small">Change gantt columns</span></a>
      <!--<a class="toggle-task-grid" type=button value="Toggle grid" onclick="toggler()"><span class="small">Toggle</span></a>-->
      <a class="edit-resource-columns" id="show-resource-columns" title="Show/hide Resource columns"><span class="small">Change resource columns</span></a>


      <a class="toggle-deadline-visibility deadlines-hidden" title="Show/hide deadline flags"><span class="small">Show deadlines</span></a>
      <a class="btn-reset-deadlines" title="Reset deadlines"><span class="small">Reset</span></a>

      <a class="toggle-baseline-visibility baselines-hidden" title="Show/hide baseline bars"><span class="small">Show baselines</span></a>
      <a class="btn-reset-baselines" title="Reset baselines"><span class="small">Reset</span></a>
		
		 <a class="toggle-workload-visibility workload-hidden" title="Show/hide workload targets"><span class="small">Show workload</span></a>
		 <a class="btn-reset-workload" title="Reset workload"><span class="small">Reset</span></a>
		 
		 
		 

      <a class="toggle-critical-path-visibility critical-path-hidden" title="Show/hide the critical path"><span class="small">Show critical path</span></a>

      <a class="toggle-calendars" data-toggle="modal" data-target="#modal_edit_calendars" title="Calendars"><span class="small">Calendars</span></a>

      </span>
    </div>

    <button id="gantt-activity-toggle" class="header-toolbar-button activity-poll" type="button" title="Show/hide activity feed"><img class="header-button-icon" src="img/svg/activity.svg">&nbsp;Activity</button>


  </div>
  <div id="resources-header-toolbar" style="display: none">
  </div>
  <div id="files-header-toolbar" style="display: none">
    
    <div style="display: inline-block;">
      <input type="text" id="file_search_text" class="form-control col-12" placeholder="Search">
      <button class="clear-file-search">Clear</div>

  </div> 
<div id="commercial-header-toolbar" style="display: none">
                             <div style="display: inline-block;">
                        <select class="dropdown-primary" id="task_commercial_select" style="width: 250px;">
								 <option value="">Select</option>
                        <?php
								foreach ($tasks_array as $task)
								{?>
                        <option value="<?=$task['id']?>"><?=$task['text']?></option>
                        <?php
							}
							?>
					</select>
                              </div>
                             

  </div>
<div id="reports-header-toolbar" style="display: none">
    <div style="display: inline-block;">
                        <select class="dropdown-primary" id="task_reports_select" style="width: 250px;">
								 <option value="">Select</option>
                        <?php
								foreach ($tasks_array as $task)
								{?>
                        <option value="<?=$task['id']?>"><?=$task['text']?></option>
                        <?php
							}
							?>
					</select>
                              </div>
  
  
<div class="nav-item nav-item-main dropdown">
              <a class="" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <button class="header-toolbar-button" id="toolbar-reports-options"><img class="header-button-icon" src="img/svg/options.svg">&nbsp;Options&nbsp;</button>
      </a>
      <span class="dropdown-menu dropdown-secondary view-dropdown-options" aria-labelledby="view-dropdown">
      <a id="toggle-reports-blocks" title="Show/hide blocks"><span class="small">Show/hide blocks</span></a>
      <a id="toggle-design-modes" title="Change design mode"><span class="small">Change design mode</span></a>
      </span>
    </div>

  </div>


  <a class="profile" title="Manage your profile details" data-toggle="modal" data-target="#modal_edit_profile">
       <span id="header-profile-name"><?=$_SESSION['user']['first_name']?></span>
    
  <img src="<?=$_SESSION['user']['avatar_url']?>" class="display-profile-image" id="header-profile-image"> </a>
</div>
<div class="hamburger-wrap">
</div>
<h4 id="toolbar-header-text">Gantt</h4>
<button class="header-toolbar-button show-messages-container" id="show-messages"><img class="header-button-icon" src="img/svg/message.svg">&nbsp;Messages&nbsp;<span class="small" id="unread-messages-count"></span></button>