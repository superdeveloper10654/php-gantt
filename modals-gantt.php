<div class="modal animated fadeIn" id="modal_task_editor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-task-editor" role="document">
    <div class="modal-content" id="modal_task_editor_content">
      <div class="modal-header">
        <h4 class="modal-title" id="task-editor-title">Task Editor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <form id="form_task_editor">
          <div class="accordion md-accordion" id="accordionTaskEditor" role="tablist" aria-multiselectable="true">
            <div class="card" id="task-editor-general-section">
              <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionTaskEditor" href="#collapseTaskEditorGeneral" aria-expanded="false" aria-controls="collapseTaskEditorGeneral">
                <p class="mb-0">General</p>
              </a>
              <div id="collapseTaskEditorGeneral" class="accordion-item-ui collapse" role="tabpanel" aria-labelledby="headingTaskEditorGeneral" data-parent="#accordionTaskEditor">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label for="task_edit_type">Type</label>
                        <select class="mdb-select dropdown-primary" name="task_edit_type" id="task_edit_type">
                          <option value="project">Project</option>
                          <option value="task">Task</option>
                          <option value="milestone">Milestone</option>
                        </select>
                      </div>
                    </div>
                    <div class="col">
                      <div class="md-form">
                        <label for="task_edit_calendar_id">Calendar</label>
                        <select class="mdb-select dropdown-primary" id="task_edit_calendar_id" style="font-size: 0.8em">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label for="">Name</label>
                        <input type="text" id="task_edit_name" name="Name" class="form-control task_edit_name reset-init" required>
                      </div>
                    </div>
                  </div>
                  <div class="row" style="display: none" id="start_dates_disabled">
                    <div class="col">
                      <span>Start date and time are disabled due to dependency</span>
                    </div>
                  </div>
                  <div class="row row-start-date">
                    <div class="col" style="padding-right: 2px;">
                      <div class="md-form" style="width: 100%">
                        <label for="task_edit_start_date_d">Start date</label>
                        <select class="mdb-select dropdown-primary reset-init" id="task_edit_start_date_d" style="border-radius: 4px 0 0 4px;">
                          <?php
                          $start_date = 1;
                          $end_date = 31;
                          for ($i = $start_date; $i <= $end_date; $i++) {
                            if ($i < 10) {
                              $i = "0" . $i;
                            }
                          ?>
                            <option value='<?= $i ?>'><?= $i ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col" style="padding-left: 2px; padding-right: 2px;">
                      <div class="md-form" style="width: 100%">
                        <select class="mdb-select dropdown-primary reset-init" id="task_edit_start_date_m" style="width: 100%">
                          <?php
                          $month_names = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                          $count = 1;
                          foreach ($month_names as $month) {
                            if ($count < 10) {
                              $count_ui = "0" . $count;
                            } else {
                              $count_ui = $count;
                            }
                          ?>
                            <option value="<?= $count_ui ?>"><?php echo $month; ?></option>
                          <?php
                            $count++;
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col" style="padding-left: 2px;">
                      <div class="md-form" style="width: 100%">
                        <select class="mdb-select dropdown-primary reset-init" id="task_edit_start_date_y" style="width: 100%">
                          <?php
                          $earliest_year = date("Y") - 3;
                          $latest_year = date("Y") + 3;
                          foreach (range($earliest_year, $latest_year) as $i) {
                          ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col start" style="padding-right: 2px;">
                      <div class="md-form">
                        <label for="task_edit_start_time_h">Start time</label>
                        <select class="mdb-select dropdown-primary reset-init" id="task_edit_start_time_h">
                          <option value="00">00</option>
                          <option value="01">01</option>
                          <option value="02">02</option>
                          <option value="03">03</option>
                          <option value="04">04</option>
                          <option value="05">05</option>
                          <option value="06">06</option>
                          <option value="07">07</option>
                          <option value="08">08</option>
                          <option value="09">09</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                          <option value="13">13</option>
                          <option value="14">14</option>
                          <option value="15">15</option>
                          <option value="16">16</option>
                          <option value="17">17</option>
                          <option value="18">18</option>
                          <option value="19">19</option>
                          <option value="20">20</option>
                          <option value="21">21</option>
                          <option value="22">22</option>
                          <option value="23">23</option>
                        </select>
                      </div>
                    </div>
                    <div class="col start" style="padding-left: 2px;">
                      <div class="md-form">
                        <select class="mdb-select dropdown-primary reset-init" id="task_edit_start_time_m" style="width: 100%">
                          <option value="00">00</option>
                          <option value="05">05</option>
                          <option value="10">10</option>
                          <option value="15">15</option>
                          <option value="20">20</option>
                          <option value="25">25</option>
                          <option value="30">30</option>
                          <option value="35">35</option>
                          <option value="40">40</option>
                          <option value="45">45</option>
                          <option value="50">50</option>
                          <option value="55">55</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row" id="workload-driven-duration-prompt" style="display: none">
                    <div class="col">
                      <span style="color: #fff">The duration is driven by the workload</span>
                    </div>
                  </div>
                  <div class="row">
                    <!--
                    <div class="row-task-editor-period-descriptors-default" style="display: inherit;">
-->
                      <div class="col" style="padding-right: 2px;">
                        <div class="md-form">
                          <label id="label_task_edit_duration_hours" for="task_edit_duration_hours">Duration (hours & mins)</label>
                          <label id="" for="task_edit_duration_hours"></label>
                          <input type="text" id="task_edit_duration_hours" name="Duration (hours)" class="form-control reset-init" placeholder="hours" style="border-radius: 4px 0 0 4px !important;">
                        </div>
                      </div>
                      <div class="col" style="padding-left: 2px;">
                        <div class="md-form">
                          <input type="text" id="task_edit_duration_mins" name="Duration (mins)" class="form-control reset-init" placeholder="mins" style="border-radius: 0 4px 4px 0 !important;">
                        </div>
                      </div>
                    <!--
                    </div>
                    -->
                    <input type="hidden" id="task_edit_timings_overriden">
                    <!--
                    <div class="row-task-editor-period-descriptors-custom" style="">
                      <div class="col">
                        <div class="md-form">
                          <label id="task_edit_custom_duration_label" for="task_edit_duration_custom">Duration (custom)</label>
                          <input type="text" id="task_edit_duration_custom" class="form-control reset-init">

                        </div>
                      </div>
                    </div>
                    -->
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label for="task_edit_end_date">Finish date & time</label>
                        <input type="text" id="task_edit_end_date" name="Finish" class="form-control reset-init">
                      </div>
                    </div>
                    <div class="col">
                      <div class="md-form" style="margin-bottom: 0">
                        <label for="cp2" style="top: -26px;">Bar colour</label>
                        <div id="cp2" class="input-group colorpicker-component" title="Using input value">
                          <input type="text" id="task_edit_bar_colour" name="Colour" class="form-control input-lg reset-init" value="" />
                          <span class="input-group-addon" style="margin-top: -3px; margin-left: -1px"><i></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="text" id="task_edit_calendar_id_init" style="width:0px; height: 0px !important; border: none;">
                </div>
              </div>
            </div>
            <div class="card" id="task-editor-resources-section">
              <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionTaskEditor" href="#collapseTaskEditorResources" aria-expanded="false" aria-controls="collapseTaskEditorResources">
                <p class="mb-0">Resources
                  <img class="task-editor-resources-locked" src="img/svg/lock-closed.svg" class="editor-locked" style="display: none;">
                </p>
              </a>
              <div id="collapseTaskEditorResources" class="collapse accordion-item-ui" role="tabpanel" aria-labelledby="headingTaskEditorResources" data-parent="#accordionTaskEditor">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label for="task_edit_resource_group_id">Resource group</label>
                        <select class="mdb-select dropdown-primary reset-init" id="task_edit_resource_group_id">
                        </select>
                        <a class="small new-resource-group">New Resource group? </a>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row" id="task-editor-resource-items-allocated">
                    <div class="col">
                      <div class="md-form">
                        <label for="task_edit_resource_id">Resource items</label>
                        <select id="task_edit_resource_id" multiple="" class="form-control reset-init" style="width: 100%">
                        </select>
                        <a class="small new-resource-item">New Resource item? </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card" id="task-editor-workload-section">
              <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionTaskEditor" href="#collapseTaskEditorWorkload" aria-expanded="false" aria-controls="collapseTaskEditorWorkload">
                <p class="mb-0">Workload<img class="task-editor-workload-locked" src="img/svg/lock-closed.svg" class="editor-locked" style="display: none;">
                </p>
              </a>
              <div id="collapseTaskEditorWorkload" class="collapse accordion-item-ui" role="tabpanel" aria-labelledby="headingTaskEditorWorkload" data-parent="#accordionTaskEditor">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <div class="md-form" style="margin: 0">
                        <div class="row">
                          <div class="col">
                            <div class="md-form">
                              <label>Total Quantity</label>
                              <input type="text" id="task_edit_workload_total_quantity" name="Total quantity" class="form-control reset-init">
                            </div>
                          </div>
                          <div class="col">
                            <div class="md-form">
                              <label>Unit of Measure</label>
                              <select class="mdb-select dropdown-primary reset-init" id="task_edit_workload_unit" style="font-size: 0.8em">
                                <option value=""></option>
                                <option value="no">no</option>
                                <option value="item">item</option>
                                <option value="mm">Millimeter (mm)</option>
                                <option value="m">Meter (m)</option>
                                <option value="km">Kilometer (km)</option>
                                <option value="m2">Square Meter (m2)</option>
                                <option value="km2">Square Kilometer (km2)</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="t">Tonne (t)</option>
                                <option value="m3">Cubic Meter (m3)</option>
                                <option value="l">Litre (l)</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="row" id="adjusted-duration-prompt" style="display: none">
                          <div class="col">
                            <span>The duration has been updated to suit this workload</span>
                          </div>
                        </div>
                        <div class="row" id="dist-workload-qty">
                          <div class="col" style="display: none;">
                            <a class="small calculate-duration-from-resource-workload">Auto-calculate Duration?</a>
                          </div>
                          <div class="col">
                            <a class="small" id="distribute-workload-quantity">Distribute Workload evenly?</a>
                          </div>
                        </div>
                        <div class="row" id="workload-breakdown">
                          <div class="col">
                            <table class="table table-hover" id="task_edit_workload_days">
                              <thead>
                                <th>Day/ Time</th>
                                <th>Quantity</th>
                                <th>Status</th>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>Mon 18th March</td>
                                  <td>100</td>
                                  <td>Done</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card" id="task-editor-status-section">
              <div class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionTaskEditor" href="#collapseTaskEditorStatus" aria-expanded="false" aria-controls="collapseTaskEditorStatus">
                <p class="mb-0">Status
                  <span id="task-editor-status-ok" style="display: none">Complete</span>
                </p>
              </div>
              <div id="collapseTaskEditorStatus" class="collapse accordion-item-ui" role="tabpanel" aria-labelledby="headingTaskEditorStatus" data-parent="#accordionTaskEditor">
                <div class="card-body">
                  <div class="row" style="">
                    <div class="col">
                      <div class="md-form">
                        <label for="task_edit_progress">Progress % input</label>
                        <input type="text" id="task_edit_progress" class="form-control">
                      </div>
                    </div>
                    <div class="col">
                      <div class="md-form">
                        <label class="task-edit-progress-ui">Progress:</label>
                        <input id="ex2" style="width: 100%; height: 30px;" data-slider-id='ex2Slider' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="0" />
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <a class="small calc-progress">Auto-calculate Progress? </a>
                    </div>
                  </div>
                  <!--
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <input type="hidden" id="task_edit_progress">
                        <input type="hidden" id="task_edit_progress">
                        <button type="button" class="mark-task-delayed col">Delayed</button>
                      </div>
                    </div>
                  </div>
-->
                  <div class="row" style="">
                    <div class="col">
                      <div class="md-form">
                        <div class="set-previous-tasks-complete-notification" style="display: none">
                          <a class="small set-previous-tasks-complete">Mark predecessors as complete?</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card" id="task-editor-comments-section">
              <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionTaskEditor" href="#collapseTaskEditorComments" aria-expanded="false" aria-controls="collapseTaskEditorComments">
                <p class="mb-0">Comments
                </p>
              </a>
              <div id="collapseTaskEditorComments" class="collapse accordion-item-ui" role="tabpanel" aria-labelledby="headingTaskEditorComments" data-parent="#accordionTaskEditor">
                <div class="card-body" id="card-comments">
                  <div class="row" id="row-comments">
                    <div class="col">
                      <div class="md-form" style="margin-top: 0">
                        <input type="text" id="new_comment_text" class="form-control reset-init" placeholder="What do you want to say?">
                        <span class="input-group-append input-group-text insert-task-comment">Add</span>
                        <button type="button" class="btn add-file-to-task"><img class="" src="img/svg/paperclip.svg"></button>
                        <input id='task_file_handler' type="file" style='display: none' onchange="updateTaskFile(this)">
                      </div>
                    </div>
                  </div>
                  <div class="row" style="padding: 10px 0 0 0">
                    <div class="col">
                      <table class="table table-hover" id="task_edit_comments">
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <table class="table table-hover" id="task_edit_files">
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card" id="task-editor-dependencies-section">
              <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionTaskEditor" href="#collapseTaskEditorDependencies" aria-expanded="false" aria-controls="collapseTaskEditorDependencies">
                <p class="mb-0">Dependencies
                  <img class="dependencies-locked" src="img/svg/lock-closed.svg" class="editor-locked" style="display: none;">
                </p>
              </a>
              <div id="collapseTaskEditorDependencies" class="collapse accordion-item-ui" role="tabpanel" aria-labelledby="headingTaskEditorDependencies" data-parent="#accordionTaskEditor">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <div class="md-form" style="margin-bottom: 0;">
                        <label class="">Predecessors</label>
                      <ul class="list-group list-group-predecessors">
                      </ul>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col">
                      <div class="md-form" style="margin-bottom: 0;">
                      <label class="">Successors</label>
                      <ul class="list-group list-group-successors">
                      </ul>
                    </div>
                    </div>
                  </div>
                  <br>
                </div>
              </div>
            </div>
            <div class="card" id="task-editor-deadline-section">
              <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionTaskEditor" href="#collapseTaskEditorDeadline" aria-expanded="false" aria-controls="collapseTaskEditorDeadline">
                <p class="mb-0">Deadline
                  <span id="task-editor-active-deadline" style="display: none">Active</span>
                </p>
              </a>
              <div id="collapseTaskEditorDeadline" class="collapse accordion-item-ui" role="tabpanel" aria-labelledby="headingTaskEditorDeadline" data-parent="#accordionTaskEditor">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <div class="row">
                        <div class="col" style="padding-right: 2px;">
                          <div class="md-form" style="width: 100%">
                            <label for="task_edit_deadline_date_d">Start date</label>
                            <select class="mdb-select dropdown-primary reset-init" id="task_edit_deadline_date_d" style="width: 100%">
                              <?php
                              $start_date = 1;
                              $end_date = 31;
                              for ($i = $start_date; $i <= $end_date; $i++) {
                                if ($i < 10) {
                                  $i = "0" . $i;
                                }
                              ?>
                                <option value='<?= $i ?>'><?= $i ?></option>
                              <?php
                              }
                              ?>
                            </select>

                          </div>
                        </div>
                        <div class="col" style="padding-left: 2px; padding-right: 2px;">
                          <div class="md-form" style="width: 100%">
                            <select class="mdb-select dropdown-primary reset-init" id="task_edit_deadline_date_m" style="width: 100%">
                              <?php
                              $month_names = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                              $count = 1;
                              foreach ($month_names as $month) {
                                if ($count < 10) {
                                  $count_ui = "0" . $count;
                                } else {
                                  $count_ui = $count;
                                }
                              ?>
                                <option value="<?= $count_ui ?>"><?php echo $month; ?></option>
                              <?php
                                $count++;
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col" style="padding-left: 2px;">
                          <div class="md-form" style="width: 100%">
                            <select class="mdb-select dropdown-primary reset-init" id="task_edit_deadline_date_y" style="width: 100%">
                              <?php
                              $earliest_year = date("Y") - 3;
                              $latest_year = date("Y") + 3;
                              foreach (range($earliest_year, $latest_year) as $i) {
                              ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                              <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col" style="padding-right: 2px;">
                          <div class="md-form">
                            <label for="task_edit_deadline_time_h">Start time</label>
                            <select class="mdb-select dropdown-primary reset-init" id="task_edit_deadline_time_h" style="width: 100%">
                              <?php
                              $earliest_hour = 0;
                              $latest_hour = 23;


                              foreach (range($earliest_hour, $latest_hour) as $i) {
                                if ($i < 10) {
                                  $i = "0" . $i;
                                }

                              ?>
                                <option value="<?= $i ?>"><?= $i ?></option>

                              <?php
                              }
                              ?>

                            </select>
                          </div>
                        </div>
                        <div class="col" style="padding-left: 2px;">
                          <div class="md-form">
                            <select class="mdb-select dropdown-primary reset-init" id="task_edit_deadline_time_m" style="width: 100%">
                              <option value="00">00</option>
                              <option value="05">05</option>
                              <option value="10">10</option>
                              <option value="15">15</option>
                              <option value="20">20</option>
                              <option value="25">25</option>
                              <option value="30">30</option>
                              <option value="35">35</option>
                              <option value="40">40</option>
                              <option value="45">45</option>
                              <option value="50">50</option>
                              <option value="55">55</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <a class="small set-deadline-to-task-dates">Use task finish date & time? </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card" id="task-editor-baseline-section">
              <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionTaskEditor" href="#collapseTaskEditorBaseline" aria-expanded="false" aria-controls="collapseTaskEditorStatus">
                <p class="mb-0">Baseline
                  <span id="task-editor-active-baseline" style="display: none">Active</span>
                </p>
              </a>
              <div id="collapseTaskEditorBaseline" class="collapse accordion-item-ui" role="tabpanel" aria-labelledby="headingTaskEditorBaseline" data-parent="#accordionTaskEditor">
                <div class="card-body">
                  <div class="row" id="adjusted-baseline-prompt" style="display: none">
                    <div class="col">
                      <span style="color: #fff">Budget has been updated to reflect the baseline dates</span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="row">
                        <div class="col" style="padding-right: 2px;">
                          <div class="md-form" style="width: 100%">
                            <label for="task_edit_baseline_start_date_d">Start date</label>
                            <select class="mdb-select dropdown-primary" id="task_edit_baseline_start_date_d" style="width: 100%">
                              <?php
                              $start_date = 1;
                              $end_date = 31;
                              for ($i = $start_date; $i <= $end_date; $i++) {
                                if ($i < 10) {
                                  $i = "0" . $i;
                                }
                              ?>
                                <option value='<?= $i ?>'><?= $i ?></option>
                              <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col" style="padding-left: 2px; padding-right: 2px;">
                          <div class="md-form" style="width: 100%">
                            <select class="mdb-select dropdown-primary" id="task_edit_baseline_start_date_m" style="width: 100%">
                              <?php
                              $month_names = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                              $count = 1;
                              foreach ($month_names as $month) {
                                if ($count < 10) {
                                  $count_ui = "0" . $count;
                                } else {
                                  $count_ui = $count;
                                }
                              ?>
                                <option value="<?= $count_ui ?>"><?php echo $month; ?></option>
                              <?php
                                $count++;
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col" style="padding-left: 2px;">
                          <div class="md-form" style="width: 100%">
                            <select class="mdb-select dropdown-primary" id="task_edit_baseline_start_date_y" style="width: 100%">
                              <?php
                              $earliest_year = date("Y") - 3;
                              $latest_year = date("Y") + 3;
                              foreach (range($earliest_year, $latest_year) as $i) {
                              ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                              <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col" style="padding-right: 2px;">
                          <div class="md-form">
                            <label for="task_edit_baseline_start_time_h">Start time</label>
                            <select class="mdb-select dropdown-primary" id="task_edit_baseline_start_time_h" style="width: 100%">
                              <option value="">Hour</option>
                              <?php
                              $earliest_hour = 0;
                              $latest_hour = 23;
                              foreach (range($earliest_hour, $latest_hour) as $i) {
                                if ($i < 10) {
                                  $i = "0" . $i;
                                }
                              ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                              <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col" style="padding-left: 2px;">
                          <div class="md-form">
                            <select class="mdb-select dropdown-primary" id="task_edit_baseline_start_time_m" style="width: 100%">
                              <option value="">Mins</option>
                              <option value="00">00</option>
                              <option value="05">05</option>
                              <option value="10">10</option>
                              <option value="15">15</option>
                              <option value="20">20</option>
                              <option value="25">25</option>
                              <option value="30">30</option>
                              <option value="35">35</option>
                              <option value="40">40</option>
                              <option value="45">45</option>
                              <option value="50">50</option>
                              <option value="55">55</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col" style="padding-right: 2px;">
                          <div class="md-form" style="width: 100%">
                            <label for="task_edit_baseline_end_date_d">Finish date</label>
                            <select class="mdb-select dropdown-primary" id="task_edit_baseline_end_date_d" style="width: 100%">
                              <?php
                              $start_date = 1;
                              $end_date = 31;
                              for ($i = $start_date; $i <= $end_date; $i++) {
                                if ($i < 10) {
                                  $i = "0" . $i;
                                }
                              ?>
                                <option value='<?= $i ?>'><?= $i ?></option>
                              <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col" style="padding-left: 2px; padding-right: 2px;">
                          <div class="md-form" style="width: 100%">
                            <select class="mdb-select dropdown-primary" id="task_edit_baseline_end_date_m" style="width: 100%">
                              <?php
                              $month_names = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                              $count = 1;
                              foreach ($month_names as $month) {
                                if ($count < 10) {
                                  $count_ui = "0" . $count;
                                } else {
                                  $count_ui = $count;
                                }
                              ?>
                                <option value="<?= $count_ui ?>"><?php echo $month; ?></option>
                              <?php
                                $count++;
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col" style="padding-left: 2px;">
                          <div class="md-form" style="width: 100%">
                            <select class="mdb-select dropdown-primary" id="task_edit_baseline_end_date_y" style="width: 100%">
                              <?php
                              $earliest_year = date("Y") - 3;
                              $latest_year = date("Y") + 3;
                              foreach (range($earliest_year, $latest_year) as $i) {
                              ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                              <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col" style="padding-right: 2px;">
                          <div class="md-form">
                            <label for="task_edit_baseline_end_time_h">Finish time</label>
                            <select class="mdb-select dropdown-primary" id="task_edit_baseline_end_time_h" style="width: 100%">
                              <option value="">Hour</option>
                              <?php
                              $earliest_hour = 0;
                              $latest_hour = 23;
                              foreach (range($earliest_hour, $latest_hour) as $i) {
                                if ($i < 10) {
                                  $i = "0" . $i;
                                }
                              ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                              <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col" style="padding-left: 2px;">
                          <div class="md-form">
                            <select class="mdb-select dropdown-primary" id="task_edit_baseline_end_time_m" style="width: 100%">
                              <option value="">Mins</option>
                              <option value="00">00</option>
                              <option value="05">05</option>
                              <option value="10">10</option>
                              <option value="15">15</option>
                              <option value="20">20</option>
                              <option value="25">25</option>
                              <option value="30">30</option>
                              <option value="35">35</option>
                              <option value="40">40</option>
                              <option value="45">45</option>
                              <option value="50">50</option>
                              <option value="55">55</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <a class="small set-baseline-to-task-dates" href="#">Use task dates? </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card" id="task-editor-finances-section">
              <a class="card-header collapsed" id="card-header-finances-section" role="tab" data-toggle="collapse" data-parent="#accordionTaskEditor" href="#collapseTaskEditorFinances" aria-expanded="false" aria-controls="collapseTaskEditorFinances">
                <p class="mb-0">Finances
                  <span id="task-editor-status-over-budget" style="display: none">Over Budget</span>
                  <img class="task-editor-finances-locked" src="img/svg/lock-closed.svg" class="editor-locked" style="display: none;">
                </p>
              </a>
              <div id="collapseTaskEditorFinances" class="collapse accordion-item-ui" role="tabpanel" aria-labelledby="headingTaskEditorFinances" data-parent="#accordionTaskEditor">
                <div class="card-body">
                  <div class="row" id="adjusted-costs-to-date-prompt" style="display: none">
                    <div class="col">
                      <span style="color: #fff">Costs have been updated to reflect the progress made</span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label for="task_edit_budget_at_completion">Budget at completion (£)</label>
                        <input type="text" id="task_edit_budget_at_completion" name="Budget at completion" class="form-control">
                      </div>
                    </div>
                    <div class="col">
                      <div class="md-form">
                        <label for="task_edit_actual_costs">Costs to date (£)</label>
                        <input type="text" id="task_edit_actual_costs" name="Actual Costs" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label for="">Price</label>
                        <input type="text" id="task_edit_price" name="Price" class="form-control task_edit_price">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <a class="small calc-task-financials">Auto-calculate Costs to date? </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer" id="task-editor-footer">
        <button class="mr-auto delete-task">Delete</button>
        <button class="save-task">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal animated fadeIn" id="modal_get_started" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Get Started</h4>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <div class="col">
              <p>We've loaded our demo project to help you get started.<br><br>It's a self-build project with some tasks, milestones, dependencies, calendars and resources to show you what Ibex can do.</p>
              <p>We recommend that you familiarise yourself with Ibex using the demo, however you may delete if you're feeling brave!</p>
              <hr>
              <div class="row">
                <div class="col">
                  <p>Let's change your profile image<br>(upload and we'll do the rest)</p>
                </div>
                <div class="col">
                  <img src="<?= $_SESSION['user']['avatar_url'] ?>" class="rounded-circle z-depth-0 " style="width: 30px; height: 30px">
                  <input type="file" id="file_handler_profile" style="display: none" onchange="setProfileImage()">
                  <button class="set-profile-image" style="margin-left: 20px">Upload</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="mr-auto delete-demo" data-toggle="modal" data-target="#modal-reset-programme">Delete Demo</button>
        <button class="get-started btn-green">Get Started</button>
      </div>
    </div>
  </div>
</div>


<div class="modal animated fadeIn" id="modal_workload_lock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Task locked</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="task_id_to_delete">
        <div class="card">
          <div class="card-body">

            <p>You cannot adjust the duration of this task whilst it has a resource group and workload output set...</p>

          </div>
        </div>
      </div>
      <div class="modal-footer">

        <button data-dismiss="modal" class="ml-auto">Close</button>
      </div>
    </div>
  </div>
</div>




<div class="modal animated fadeIn" id="modal_delete_task" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Are your sure?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="task_id_to_delete">
        <div class="card">
          <div class="card-body">
            <p class="delete-task-text">You are about to delete<br></p>
            <p>Do you want Ibex to automagically re-schedule your adjacent tasks<br>with dependencies (as appropriate)?</p>
            <div class="md-form">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="auto_schedule_after_delete" checked="">
                <label class="form-check-label" for="auto_schedule_after_delete">&nbsp; &nbsp; &nbsp; &nbsp; Delete this, then re-schedule</label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="mr-auto cancel-delete-task">Cancel</button>
        <button class="confirm-delete-task">Delete</button>
      </div>
    </div>
  </div>
</div>

<div class="modal animated fadeIn" id="modal_link_editor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Dependency Editor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body" style="overflow: hidden">
        <input type="hidden" id="link_edit_new" value="true">
        <input type="hidden" id="link_edit_id">
        <input type="hidden" id="link_edit_source_task_id">
        <input type="hidden" id="link_edit_target_task_id">
        <input type="hidden" id="link_edit_source_task_guid">
        <input type="hidden" id="link_edit_target_task_guid">
        <input type="hidden" id="proposed_target_start_date">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="md-form">
                  <select class="mdb-select dropdown-primary" id="link_edit_type" style="font-size: 0.8em">
                    <option value="0">Finish to Start</option>
                    <option value="1">Start to Start</option>
                    <option value="2">Finish to Finish</option>
                    <option value="3">Start to Finish</option>
                  </select>
                  <label for="link_edit_type">Dependency type</label>
                </div>
              </div>
              <div class="col" style="padding: 20px; text-align: center;">
                <img id="F2S-image" src="img/svg/F2S.svg" tyle="display: none">
                <img id="S2S-image" src="img/svg/S2S.svg" style="display: none">
                <img id="F2F-image" src="img/svg/F2F.svg" style="display: none">
                <img id="S2F-image" src="img/svg/S2F.svg" style="display: none">
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="md-form">
                  <select class="mdb-select dropdown-primary" id="link_edit_offset_type">
                    <option value="0">None</option>
                    <option value="1">Lag</option>
                    <option value="2">Lead</option>
                  </select>
                  <label for="link_edit_type">Offset type</label>
                </div>
              </div>
              <div class="col leftside" id="link-edit-hours" style="display: none;">
                <div class="md-form">
                  <input type="text" id="link_edit_duration_hours" class="form-control" style="float: left" placeholder="hours">
                  <label for="link_edit_duration_hours">Offset by (hours & mins)</label>
                </div>
              </div>
              <div class="col rightside" id="link-edit-mins" style="display: none;">
                <div class="md-form">
                  <input type="text" id="link_edit_duration_mins" class="form-control" style="float: left" placeholder="mins">
                </div>
              </div>
              <div class="col" id="link-edit-custom">
                <div class="md-form">
                  <input type="text" id="link_edit_duration_custom" class="form-control" style="float: left" value="0">
                  <label id="link_edit_duration_custom_label" for="link_edit_duration_custom">Offset duration (shifts)</label>
                </div>
              </div>
              <input type="hidden" id="link_edit_offset" value="0">
            </div>
            <hr>
            <div class="row">
              <div class="col">
                <p class="small link-edit-timings">Task 1 finishes on x at y<br>Task 2 will start at A on B</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="mr-auto delete-link">Delete</button>
        <button class="save-link">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal animated fadeIn" id="modal_date_range" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Date Range Editor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body" style="overflow: hidden;">
        <div class="card">
          <div class="card-body">
            <p>Select the visible date range of the Gantt bars pane.</p>
            <div class="row">
              <div class="col">
                <div class="md-form" style="width: 100%">
                  <label for="task_edit_start_date_d">Date from</label>
                  <select class="mdb-select dropdown-primary" id="date_range_start_date_d" style="width: 100%">
                    <?php
                    $start_date = 1;
                    $end_date = 31;
                    for ($i = $start_date; $i <= $end_date; $i++) {
                      if ($i < 10) {
                        $i = "0" . $i;
                      }
                    ?>
                      <option value='<?= $i ?>'><?= $i ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="md-form" style="width: 100%">
                  <select class="mdb-select dropdown-primary" id="date_range_start_date_m" style="width: 100%">
                    <?php
                    $month_names = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                    $count = 1;
                    foreach ($month_names as $month) {
                      if ($count < 10) {
                        $count_ui = "0" . $count;
                      } else {
                        $count_ui = $count;
                      }
                    ?>
                      <option value="<?= $count_ui ?>"><?php echo $month; ?></option>
                    <?php
                      $count++;
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="md-form" style="width: 100%">
                  <select class="mdb-select dropdown-primary" id="date_range_start_date_y" style="width: 100%">
                    <?php
                    $earliest_year = date("Y") - 3;
                    $latest_year = date("Y") + 3;
                    foreach (range($earliest_year, $latest_year) as $i) {
                    ?>
                      <option value="<?= $i ?>"><?= $i ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="md-form" style="width: 100%">
                  <label for="task_edit_start_date_d">Date to</label>
                  <select class="mdb-select dropdown-primary" id="date_range_end_date_d" style="width: 100%">
                    <?php
                    $start_date = 1;
                    $end_date = 31;
                    for ($i = $start_date; $i <= $end_date; $i++) {
                      if ($i < 10) {
                        $i = "0" . $i;
                      }
                    ?>
                      <option value='<?= $i ?>'><?= $i ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="md-form" style="width: 100%">
                  <select class="mdb-select dropdown-primary" id="date_range_end_date_m" style="width: 100%">
                    <?php
                    $month_names = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                    $count = 1;
                    foreach ($month_names as $month) {
                      if ($count < 10) {
                        $count_ui = "0" . $count;
                      } else {
                        $count_ui = $count;
                      }
                    ?>
                      <option value="<?= $count_ui ?>"><?php echo $month; ?></option>
                    <?php
                      $count++;
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="md-form" style="width: 100%">
                  <select class="mdb-select dropdown-primary" id="date_range_end_date_y" style="width: 100%">
                    <?php
                    $earliest_year = date("Y") - 3;
                    $latest_year = date("Y") + 3;
                    foreach (range($earliest_year, $latest_year) as $i) {
                    ?>
                      <option value="<?= $i ?>"><?= $i ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <p>Click on the 'Default' button to show the default two-month range.</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="mr-auto set-default-date-range">Default</button>
        <button class="set-date-range">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal animated fadeIn" id="modal_prevent_drag" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Sorry, you can't do that</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <p>You've'dragged this task to change its scheduled dates, however your change was prevented by this task's dependency with another task.</p>
            <p>If you need to change the dates, you'll need to edit or delete the dependency to suit.</p>
            <p>Click 'Edit' below to edit the dependency, or click 'Revert' to let Ibex's auto-scheduler work its magic.</p>
            <div class="row">
              <div class="col">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="mr-auto override-edit-dependency" data-id=''>Edit</button>
        <button data-dismiss="modal" class="">Revert</button>
      </div>
    </div>
  </div>
</div>

<div class="modal animated fadeIn" id="modal-reset-programme" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Are you sure?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <div class="col">
              <div class="row">
                <p>You are about to delete all of your project data (tasks, milestones, dependencies, resources, files, calendars and activity records).<br><br><strong>This can't be undone - are you sure?</strong><br><br>We'll keep your collaborative
                  stuff (team members, team messages, broadcasts and permissions settings).</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="mr-auto cancel-programme-confirm">Cancel</button>
        <button class="reset-programme-confirm">Confirm Delete</button>
      </div>
    </div>
  </div>
</div>

<div class="modal animated fadeIn" id="modal_resource_clash" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Resource Contraint</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <input type="hidden" id="clashed_resource_id">
            <input type="hidden" id="clashed_resource_task_id">
            <p class="clash-intro"></p>
            <p class="clash-text"></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="mr-auto clash-resource-unassign">Unassign resource</button>
        <button type="button" class="clash-resource-revert">Cancel</button>
      </div>
    </div>
  </div>
</div>



<div class="modal animated fadeIn" id="modal_add_to_workload_root" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Workload Target Editor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <p class="" id="workload_root_summary"></p>
            <div class="row">
              <div class="col">
                <div class="md-form" style="">
                  <select class="mdb-select dropdown-primary" id="workload_child_target_hour">
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                  </select>
                  <label for="workload_child_target_hour" class="active">Target hour</label>
                </div>
              </div>
              <div class="col">
                <div class="md-form" style="">
                  <select class="mdb-select dropdown-primary" id="workload_child_target_minute" style="border-radius: 0 4px 4px 0 !important;">
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                    <option value="32">32</option>
                    <option value="33">33</option>
                    <option value="34">34</option>
                    <option value="35">35</option>
                    <option value="36">36</option>
                    <option value="37">37</option>
                    <option value="38">38</option>
                    <option value="39">39</option>
                    <option value="40">40</option>
                    <option value="41">41</option>
                    <option value="42">42</option>
                    <option value="43">43</option>
                    <option value="44">44</option>
                    <option value="45">45</option>
                    <option value="46">46</option>
                    <option value="47">47</option>
                    <option value="48">48</option>
                    <option value="49">49</option>
                    <option value="50">50</option>
                    <option value="51">51</option>
                    <option value="52">52</option>
                    <option value="53">53</option>
                    <option value="54">54</option>
                    <option value="55">55</option>
                    <option value="56">56</option>
                    <option value="57">57</option>
                    <option value="58">58</option>
                    <option value="59">59</option>
                  </select>
                  <label for="workload_child_target_minute" class="active">Target minute</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="md-form" style="">
                  <input type="text" id="workload_child_quantity" class="form-control col-12" placeholder="Quantity">
                  <label for="workload_child_quantity" class="active">Target quantity</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="" type="button" id="workload_root_submit" data-root='0'>Add</button>
      </div>
    </div>
  </div>
</div>

<div class="modal animated fadeIn" id="modal_edit_task_columns" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Gantt Columns Editor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <p>Choose which columns to show in the left-hand pane</p>
            <table id="table_task_columns" class="table table-sm">
              <tbody>
                <tr data-index="wbs">
                  <td><span>WBS</span></td>
                  <td><input class="form-check-input " type="checkbox" id="task_column_wbs">
                    <label class="form-check-label" for="task_column_wbs" class="label-table"></label></td>
                </tr>
                <tr data-index="text">
                  <td><span>Name</span></td>
                  <td><input class="form-check-input " type="checkbox" id="task_column_text">
                    <label class="form-check-label" for="task_column_text" class="label-table"></label></td>
                </tr>
                <tr data-index="start_date">
                  <td><span>Start date</span></td>
                  <td><input class="form-check-input " type="checkbox" id="task_column_start_date">
                    <label class="form-check-label" for="task_column_start_date" class="label-table"></label></td>
                </tr>
                <tr data-index="end_date">
                  <td><span>Finish date</span></td>
                  <td><input class="form-check-input " type="checkbox" id="task_column_end_date">
                    <label class="form-check-label" for="task_column_end_date" class="label-table"></label></td>
                </tr>
                <tr data-index="progress">
                  <td><span>Progress</span></td>
                  <td><input class="form-check-input " type="checkbox" id="task_column_progress">
                    <label class="form-check-label" for="task_column_progress" class="label-table"></label></td>
                </tr>
                <tr data-index="duration_worked">
                  <td><span>Duration</span></td>
                  <td><input class="form-check-input " type="checkbox" id="task_column_duration">
                    <label class="form-check-label" for="task_column_duration" class="label-table"></label></td>
                </tr>
                <tr data-index="baseline_start">
                  <td><span>Baseline start date</span></td>
                  <td><input class="form-check-input " type="checkbox" id="task_column_baseline_start_date">
                    <label class="form-check-label" for="task_column_baseline_start_date" class="label-table"></label></td>
                </tr>
                <tr data-index="baseline_end">
                  <td><span>Baseline finish date</span></td>
                  <td><input class="form-check-input " type="checkbox" id="task_column_baseline_end_date">
                    <label class="form-check-label" for="task_column_baseline_end_date" class="label-table"></label></td>
                </tr>
                <tr data-index="task_calendar">
                  <td><span>Calendar</span></td>
                  <td><input class="form-check-input " type="checkbox" id="task_column_calendar">
                    <label class="form-check-label" for="task_column_calendar" class="label-table"></label></td>
                </tr>
                <tr data-index="deadline">
                  <td><span>Deadline</span></td>
                  <td><input class="form-check-input " type="checkbox" id="task_column_deadline">
                    <label class="form-check-label" for="task_column_deadline" class="label-table"></label></td>
                </tr>
                <tr data-index="resource_id">
                  <td><span>Resources</span></td>
                  <td><input class="form-check-input " type="checkbox" id="task_column_resources">
                    <label class="form-check-label" for="task_column_resources" class="label-table"></label></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="save-task-columns">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal animated fadeIn" id="modal_reset_baselines" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Baselines Editor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <p>Select the applicable date range for resetting the baselines</p>
            <div class="row">
              <div class="col">
                <div class="md-form">
                  <input type="text" id="reset_baselines_from" class="form-control">
                  <label for="reset_baselines_from" class="active">Date from</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="md-form">
                  <input type="text" id="reset_baselines_to" class="form-control">
                  <label for="reset_baselines_to" class="active">Date to</label>
                </div>
              </div>
            </div>
            <p>Click on 'Reset All' to reset all task baselines and deadlines</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="mr-auto reset-all-baselines">Reset All</button>
        <button type="button" class="reset-baselines">Reset</button>
      </div>
    </div>
  </div>
</div>