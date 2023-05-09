  <div class="modal animated fadeIn" id="modal_task_calendar_editor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 2000">
    <div class="modal-dialog modal-info" role="document">
      <div class="modal-content">
        <div class="modal-header">
                       <h4 class="modal-title">Task Calendar Editor</h4>
          <button type="button" class="close reopen-all-calendars" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
       <div class="modal-body">
         <form>
        <div class="accordion md-accordion" id="accordionTaskCalendarEditor" role="tablist" aria-multiselectable="true">
          <div class="card">
            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionTaskCalendarEditor" href="#collapseTaskCalendarEditorGeneral" aria-expanded="false" aria-controls="collapseTaskCalendarEditorGeneral">
              <p class="mb-0">
                <img class="header-icon" src="img/svg/calendar-edit.svg"> General<i class="fas fa-angle-down rotate-icon"></i>
              </p>
            </a>
            <div id="collapseTaskCalendarEditorGeneral" class="" role="tabpanel" aria-labelledby="headingTaskCalendarEditorGeneral" data-parent="#accordionTaskCalendarEditor">
              <div class="card-body">
          <input type="hidden" id="task_calendar_edit_id" value="0">
                <div class="row">
                  <div class="col">
                    <div class="md-form" class="calendar_edit_name">
                      <input type="text" class="form-control calendar-edit-name" id="task_calendar_edit_name" placeholder="Give this calendar a name">
                      <label for="task_calendar_edit_name">Calendar name</label>
                    </div>
                  </div>
                </div>
                <div class="row" style="display: none;">
                  <div class="col">
                    <div class="md-form" id="calendar_type">
                      <select class="mdb-select dropdown-primary" id="">
								<option value="1" selected>Task calendar</option>
									</select>
                      <label for="task_calendar_edit_type">Type</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col md-form" style="margin: 0">
                    <table style="width: 50%" class="table table-sm">
                      <tbody>
                        <tr>
                          <td><span>Set as default</span></td>
                          <td><input class="form-check-input " type="checkbox" id="task_calendar_edit_default">
                            <label class="form-check-label" for="task_calendar_edit_default" class="label-table"></label></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            </div>
              <div class="card">
            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionTaskCalendarWorking" href="#collapseTaskCalendarEditorWorking" aria-expanded="false" aria-controls="collapseTaskCalendarEditorWorking">
              <p class="mb-0">
                <img class="header-icon" src="img/svg/calendar-normal.svg"> Normal Working<i class="fas fa-angle-down rotate-icon"></i>
              </p>
            </a>
            <div id="collapseTaskCalendarEditorWorking" class="collapse" role="tabpanel" aria-labelledby="headingTaskCalendarEditorWorking" data-parent="#accordionTaskCalendarEditor">
              <div class="card-body">
                <p>Select the normal working days with start and finish times below</p>
                <div class="row">
                  <div class="col md-form" style="margin: 0">
                    <table class="table table-sm" id="week_days">
                      <tbody>
                        <tr>
                          <td><span>Monday</span></td>
                          <td><input class="form-check-input" id="task_calendar_edit_working_day_monday" type="checkbox">
                            <label class="form-check-label" for="task_calendar_edit_working_day_monday" class="label-table"></label></td>
                        </tr>
                        <tr>
                          <td><span>Tuesday</span></td>
                          <td><input class="form-check-input" id="task_calendar_edit_working_day_tuesday" type="checkbox">
                            <label class="form-check-label" for="task_calendar_edit_working_day_tuesday" class="label-table"></label></td>
                        </tr>
                        <tr>
                          <td><span>Wednesday</span></td>
                          <td><input class="form-check-input" id="task_calendar_edit_working_day_wednesday" type="checkbox">
                            <label class="form-check-label" for="task_calendar_edit_working_day_wednesday" class="label-table"></label></td>
                        </tr>
                        <tr>
                          <td><span>Thursday</span></td>
                          <td><input class="form-check-input" id="task_calendar_edit_working_day_thursday" type="checkbox">
                            <label class="form-check-label" for="task_calendar_edit_working_day_thursday" class="label-table"></label></td>
                        </tr>
                        <tr>
                          <td><span>Friday</span></td>
                          <td><input class="form-check-input" id="task_calendar_edit_working_day_friday" type="checkbox">
                            <label class="form-check-label" for="task_calendar_edit_working_day_friday" class="label-table"></label></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col md-form" style="margin: 0">
                    <table class="table table-sm" id="weekend_days">
                      <tbody>
                        <tr>
                          <td><span>Saturday</span></td>
                          <td><input class="form-check-input" id="task_calendar_edit_working_day_saturday" type="checkbox">
                            <label class="form-check-label" for="task_calendar_edit_working_day_saturday" class="label-table"></label></td>
                        </tr>
                        <tr>
                          <td><span>Sunday</span></td>
                          <td><input class="form-check-input" id="task_calendar_edit_working_day_sunday" type="checkbox">
                            <label class="form-check-label" for="task_calendar_edit_working_day_sunday" class="label-table"></label></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-6">
                    <div class="md-form">
                      <input type="text" class="form-control" id="calendar_edit_start_time" placeholder="07:00">
                      <label for="calendar_edit_start_time">Start time</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="md-form">
                      <input type="text" class="form-control" id="calendar_edit_end_time" placeholder="17:00">
                      <label for="calendar_edit_end_time">Finish time</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
            <div class="card" style="border-bottom: none !important;">
            <a id="task_editor_overrides_header" style="display: none" class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionTaskCalendarOverrides" href="#collapseTaskCalendarEditorOverrides" aria-expanded="false" aria-controls="collapseCalendarEditorOverrides">
              <p class="mb-0">
                <img class="header-icon" src="img/svg/calendar-override.svg"> Overrides<i class="fas fa-angle-down rotate-icon"></i>
              </p>
            </a>
            <div id="collapseTaskCalendarEditorOverrides" class="collapse" role="tabpanel" aria-labelledby="headingTaskCalendarEditorOverrides" data-parent="#accordionTaskCalendarEditor">
              <div class="card-body">
                <p>Select the overriding non-working periods below</p>
					 
					 
					 
						  <div class="row">
                    <div class="col" style="padding-right: 2px;">
                      <div class="md-form" style="width: 100%">
                        <label for="task_edit_start_date_d">Override start date</label>
                        <select class="mdb-select dropdown-primary" id="task_calendar_edit_override_start_d" style="border-radius: 4px 0 0 4px;">
										 <?php 
							          $start_date = 1;
							          $end_date = 31;
							          for( $i=$start_date; $i<=$end_date; $i++ ) 
										 {
											 if ($i < 10)
												{
													$i = "0" . $i;
												}
														 ?>
							           <option value='<?=$i?>'><?=$i?></option>
									        <?php
										  }
												 ?>
					</select>
                      </div>
                    </div>
						  
						  
                    <div class="col" style="padding-left: 2px; padding-right: 2px;">
                      <div class="md-form" style="width: 100%">
                        <select class="mdb-select dropdown-primary" id="task_calendar_edit_override_start_m" style="width: 100%">
					<?php
					$month_names = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
					$count = 1;
					foreach($month_names as $month)
					{
						if ($count < 10)
						{
							$count_ui = "0" . $count;
						}
						else {
							$count_ui = $count;
						}
					?>
					<option value="<?=$count_ui?>"><?php echo $month; ?></option>
					<?php 
					$count++;
					}
					?>
					</select>
                      </div>
                    </div>
                    <div class="col" style="padding-left: 2px;">
                      <div class="md-form" style="width: 100%">
                        <select class="mdb-select dropdown-primary" id="task_calendar_edit_override_start_y" style="width: 100%">
				<?php
				 $earliest_year = date("Y") - 10;
				$latest_year = date("Y") + 10;
				 foreach ( range( $earliest_year, $latest_year ) as $i ) 
				 {
				 ?>
						<option value="<?=$i?>"><?=$i?></option>
						<?php
					}
					?>
					</select>
                      </div>
                    </div>
					  </div>
					
					  
					  
						  
						  
						  
						  
						    <div class="row">
                    <div class="col" style="padding-right: 2px;">
                      <div class="md-form" style="width: 100%">
                        <label for="task_edit_start_date_d">Override end date</label>
                        <select class="mdb-select dropdown-primary" id="task_calendar_edit_override_end_d" style="border-radius: 4px 0 0 4px;">
										 <?php 
							          $start_date = 1;
							          $end_date = 31;
							          for( $i=$start_date; $i<=$end_date; $i++ ) 
										 {
											 if ($i < 10)
												{
													$i = "0" . $i;
												}
														 ?>
							           <option value='<?=$i?>'><?=$i?></option>
									        <?php
										  }
												 ?>
					</select>
                      </div>
                    </div>
                    <div class="col" style="padding-left: 2px; padding-right: 2px;">
                      <div class="md-form" style="width: 100%">
                        <select class="mdb-select dropdown-primary" id="task_calendar_edit_override_end_m" style="width: 100%">
					<?php
					$month_names = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
					$count = 1;
					foreach($month_names as $month)
					{
						if ($count < 10)
						{
							$count_ui = "0" . $count;
						}
						else {
							$count_ui = $count;
						}
					?>
					<option value="<?=$count_ui?>"><?php echo $month; ?></option>
					<?php 
					$count++;
					}
					?>
					</select>
                      </div>
                    </div>
                    <div class="col" style="padding-left: 2px;">
                      <div class="md-form" style="width: 100%">
                        <select class="mdb-select dropdown-primary" id="task_calendar_edit_override_end_y" style="width: 100%">
				<?php
				 $earliest_year = date("Y") - 10;
				$latest_year = date("Y") + 10;
				 foreach ( range( $earliest_year, $latest_year ) as $i ) 
				 {
				 ?>
						<option value="<?=$i?>"><?=$i?></option>
						<?php
					}
					?>
					</select>
                      </div>
						 </div>
						 </div>
						 
						 
						 
						 
						  
                    
						  
								  
								 	  <div class="row"> 
                  <div class="col" style="flex: none; width: 20%;">
                    <div class="md-form">
                      <button type="button" class="task-add-calendar-override">Add</button>
                    </div>
                  </div>
                </div>
                <hr>
                <table id="table_task_calendar_overrides" class="table table-sm">
                  <thead>
                    <tr>
                      <th style="width: 80%">Dates</th>
                      <th style="width: 20%"></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
         </form>
        </div>
        <div class="modal-footer">
          <button class="mr-auto" class="delete-calendar" id="delete-task-calendar" style="display: none">Delete</button>
          <button type="button" id="save-task-calendar">Save</button>
        </div>
      </div>
    </div>
  </div>














 <div class="modal animated fadeIn" id="modal_resource_calendar_editor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 2000">
    <div class="modal-dialog modal-info" role="document">
      <div class="modal-content">
        <div class="modal-header">
                       <h4 class="modal-title">Resource Calendar Editor</h4>
          <button type="button" class="close reopen-all-calendars" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
       <div class="modal-body">
        <div class="accordion md-accordion" id="accordionResourceCalendarEditor" role="tablist" aria-multiselectable="true">
          <div class="card">
            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionResourceCalendarEditor" href="#collapseResourceCalendarEditorGeneral" aria-expanded="false" aria-controls="collapseResourceCalendarEditorGeneral">
              <p class="mb-0">
                <img class="header-icon" src="img/svg/calendar-edit.svg"> General<i class="fas fa-angle-down rotate-icon"></i>
              </p>
            </a>
            <div id="collapseResourceCalendarEditorGeneral" class="" role="tabpanel" aria-labelledby="headingResourceCalendarEditorGeneral" data-parent="#accordionResourceCalendarEditor">
              <div class="card-body">
          <input type="hidden" id="resource_calendar_edit_id" value="0">
                <div class="row">
                  <div class="col">
                    <div class="md-form" class="calendar_edit_name">
                      <input type="text" class="form-control calendar-edit-name" id="resource_calendar_edit_name" placeholder="Give this calendar a name">
                      <label for="resource_calendar_edit_name">Calendar name</label>
                    </div>
                  </div>
                </div>
                <div class="row" style="display: none;">
                  <div class="col">
                    <div class="md-form" id="calendar_type">
                      <select class="mdb-select dropdown-primary" id="">
								<option value="2" selected>Resource calendar</option>
									</select>
                      <label for="resource_calendar_edit_type">Type</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col md-form" style="margin: 0">
                    <table style="width: 50%" class="table table-sm">
                      <tbody>
                        <tr>
                          <td><span>Set as default</span></td>
                          <td><input class="form-check-input " type="checkbox" id="resource_calendar_edit_default">
                            <label class="form-check-label" for="resource_calendar_edit_default" class="label-table"></label></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            </div>
              <div class="card">
            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionResourceCalendarEditor" href="#collapseResourceCalendarEditorWorking" aria-expanded="false" aria-controls="collapseResourceCalendarEditorWorking">
              <p class="mb-0">
                <img class="header-icon" src="img/svg/calendar-normal.svg"> Normal Working<i class="fas fa-angle-down rotate-icon"></i>
              </p>
            </a>
            <div id="collapseResourceCalendarEditorWorking" class="collapse" role="tabpanel" aria-labelledby="headingResourceCalendarEditorWorking" data-parent="#accordionResourceCalendarEditor">
              <div class="card-body">
                <p>Select the normal working days with start and finish times below</p>
                <div class="row">
                  <div class="col md-form" style="margin: 0">
                    <table class="table table-sm" id="week_days">
                      <tbody>
                        <tr>
                          <td><span>Monday</span></td>
                          <td><input class="form-check-input" id="resource_calendar_edit_working_day_monday" type="checkbox">
                            <label class="form-check-label" for="resource_calendar_edit_working_day_monday" class="label-table"></label></td>
                        </tr>
                        <tr>
                          <td><span>Tuesday</span></td>
                          <td><input class="form-check-input" id="resource_calendar_edit_working_day_tuesday" type="checkbox">
                            <label class="form-check-label" for="resource_calendar_edit_working_day_tuesday" class="label-table"></label></td>
                        </tr>
                        <tr>
                          <td><span>Wednesday</span></td>
                          <td><input class="form-check-input" id="resource_calendar_edit_working_day_wednesday" type="checkbox">
                            <label class="form-check-label" for="resource_calendar_edit_working_day_wednesday" class="label-table"></label></td>
                        </tr>
                        <tr>
                          <td><span>Thursday</span></td>
                          <td><input class="form-check-input" id="resource_calendar_edit_working_day_thursday" type="checkbox">
                            <label class="form-check-label" for="resource_calendar_edit_working_day_thursday" class="label-table"></label></td>
                        </tr>
                        <tr>
                          <td><span>Friday</span></td>
                          <td><input class="form-check-input" id="resource_calendar_edit_working_day_friday" type="checkbox">
                            <label class="form-check-label" for="resource_calendar_edit_working_day_friday" class="label-table"></label></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col md-form" style="margin: 0">
                    <table class="table table-sm" id="weekend_days">
                      <tbody>
                        <tr>
                          <td><span>Saturday</span></td>
                          <td><input class="form-check-input" id="resource_calendar_edit_working_day_saturday" type="checkbox">
                            <label class="form-check-label" for="resource_calendar_edit_working_day_saturday" class="label-table"></label></td>
                        </tr>
                        <tr>
                          <td><span>Sunday</span></td>
                          <td><input class="form-check-input" id="resource_calendar_edit_working_day_sunday" type="checkbox">
                            <label class="form-check-label" for="resource_calendar_edit_working_day_sunday" class="label-table"></label></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-6">
                    <div class="md-form">
                      <input type="text" class="form-control clockpicker" id="resource_calendar_edit_start_time" placeholder="07:00">
                      <label for="resource_calendar_edit_start_time">Start time</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="md-form">
                      <input type="text" class="form-control clockpicker" id="resource_calendar_edit_end_time" placeholder="17:00">
                      <label for="resource_calendar_edit_end_time">Finish time</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
            <div class="card" style="border-bottom: none !important;">
            <a id="resource_editor_overrides_header" class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionResourceCalendarOverrides" href="#collapseResourceCalendarEditorOverrides" aria-expanded="false" aria-controls="collapseCalendarEditorOverrides">
              <p class="mb-0">
                <img class="header-icon" src="img/svg/calendar-override.svg"> Overrides<i class="fas fa-angle-down rotate-icon"></i>
              </p>
            </a>
            <div id="collapseResourceCalendarEditorOverrides" class="collapse" role="tabpanel" aria-labelledby="headingResourceCalendarEditorOverrides" data-parent="#accordionResourceCalendarEditor">
              <div class="card-body">
                <p>Select the overriding non-working periods below</p>
					 
					 
						  <div class="row">
                    <div class="col" style="padding-right: 2px;">
                      <div class="md-form" style="width: 100%">
                        <label for="task_edit_start_date_d">Override start date</label>
                        <select class="mdb-select dropdown-primary" id="resource_calendar_edit_override_start_d" style="border-radius: 4px 0 0 4px;">
										 <?php 
							          $start_date = 1;
							          $end_date = 31;
							          for( $i=$start_date; $i<=$end_date; $i++ ) 
										 {
											 if ($i < 10)
												{
													$i = "0" . $i;
												}
														 ?>
							           <option value='<?=$i?>'><?=$i?></option>
									        <?php
										  }
												 ?>
					</select>
                      </div>
                    </div>
						  
						  
                    <div class="col" style="padding-left: 2px; padding-right: 2px;">
                      <div class="md-form" style="width: 100%">
                        <select class="mdb-select dropdown-primary" id="resource_calendar_edit_override_start_m" style="width: 100%">
					<?php
					$month_names = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
					$count = 1;
					foreach($month_names as $month)
					{
						if ($count < 10)
						{
							$count_ui = "0" . $count;
						}
						else {
							$count_ui = $count;
						}
					?>
					<option value="<?=$count_ui?>"><?php echo $month; ?></option>
					<?php 
					$count++;
					}
					?>
					</select>
                      </div>
                    </div>
                    <div class="col" style="padding-left: 2px;">
                      <div class="md-form" style="width: 100%">
                        <select class="mdb-select dropdown-primary" id="resource_calendar_edit_override_start_y" style="width: 100%">
				<?php
				 $earliest_year = date("Y") - 10;
				$latest_year = date("Y") + 10;
				 foreach ( range( $earliest_year, $latest_year ) as $i ) 
				 {
				 ?>
						<option value="<?=$i?>"><?=$i?></option>
						<?php
					}
					?>
					</select>
                      </div>
                    </div>
					  </div>
					
					  
					  
						  
						  
						  
						  
						    <div class="row">
                    <div class="col" style="padding-right: 2px;">
                      <div class="md-form" style="width: 100%">
                        <label for="task_edit_start_date_d">Override end date</label>
                        <select class="mdb-select dropdown-primary" id="resource_calendar_edit_override_end_d" style="border-radius: 4px 0 0 4px;">
										 <?php 
							          $start_date = 1;
							          $end_date = 31;
							          for( $i=$start_date; $i<=$end_date; $i++ ) 
										 {
											 if ($i < 10)
												{
													$i = "0" . $i;
												}
														 ?>
							           <option value='<?=$i?>'><?=$i?></option>
									        <?php
										  }
												 ?>
					</select>
                      </div>
                    </div>
                    <div class="col" style="padding-left: 2px; padding-right: 2px;">
                      <div class="md-form" style="width: 100%">
                        <select class="mdb-select dropdown-primary" id="resource_calendar_edit_override_end_m" style="width: 100%">
					<?php
					$month_names = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
					$count = 1;
					foreach($month_names as $month)
					{
						if ($count < 10)
						{
							$count_ui = "0" . $count;
						}
						else {
							$count_ui = $count;
						}
					?>
					<option value="<?=$count_ui?>"><?php echo $month; ?></option>
					<?php 
					$count++;
					}
					?>
					</select>
                      </div>
                    </div>
                    <div class="col" style="padding-left: 2px;">
                      <div class="md-form" style="width: 100%">
                        <select class="mdb-select dropdown-primary" id="resource_calendar_edit_override_end_y" style="width: 100%">
				<?php
				 $earliest_year = date("Y") - 10;
				$latest_year = date("Y") + 10;
				 foreach ( range( $earliest_year, $latest_year ) as $i ) 
				 {
				 ?>
						<option value="<?=$i?>"><?=$i?></option>
						<?php
					}
					?>
					</select>
                      </div>
						 </div>
						 </div>
						 
						 
						 
						 
                <div class="row">
						 
                  <div class="col" style="flex: none; width: 20%;">
                    <div class="md-form">
                      <button class="resource-add-calendar-override">Add</button>
                    </div>
                  </div>
                </div>
                <hr>
                <table id="table_resource_calendar_overrides" class="table table-sm">
                  <thead>
                    <tr>
                      <th style="width: 80%">Dates</th>
                      <th style="width: 20%"></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        </div>
        <div class="modal-footer">
          <button class="mr-auto" class="delete-calendar" id="delete-resource-calendar" style="display: none">Delete</button>
          <button type="button" id="save-resource-calendar">Save</button>
        </div>
      </div>
    </div>
  </div>


























  <div class="modal animated fadeIn" id="modal_edit_calendars" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-info" role="document">
      <div class="modal-content">
        <div class="modal-header">
                       <h4 class="modal-title">Calendars Editor</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
        <div class="modal-body">
          <div class="card">
            <div class="card-body">
              <p>Edit your existing calendars</p>
              <table id="table_calendars" class="table table-striped">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
              </table>
              <hr>
              <p>Add a new calendar</p>
              <button id="calendars-modal-add-task-calendar" class="add-task-calendar">Task Calendar<img id="task-calendars-modal-add-calendar-locked" src="img/svg/lock-closed.svg" style="display: none;"></button><br><br>
              <button id="calendars-modal-add-resource-calendar" class="add-resource-calendar">Resource Calendar<img id="resource-calendars-modal-add-calendar-locked" src="img/svg/lock-closed.svg" style="display: none;"></button>

            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="close-calendars-modal" data-dismiss="modal" aria-label="Close">Save</button>
        </div>
      </div>
    </div>
  </div>

