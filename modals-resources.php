<div class="modal animated fadeIn" id="modal_resource_editor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 1998">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
                       <h4 class="modal-title">Resources Editor</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="accordion md-accordion" id="accordionResourceEditor" role="tablist" aria-multiselectable="true">
          <div class="card">
            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionResourceEditor" href="#collapseResourceEditorGeneral" aria-expanded="false" aria-controls="collapseResourceEditorGeneral">
              <p class="mb-0">
                <img class="header-icon" src="img/svg/general.svg"> General<i class="fas fa-angle-down rotate-icon"></i>
              </p>
            </a>
            <div id="collapseResourceEditorGeneral" class="accordion-item-ui" role="tabpanel" aria-labelledby="headingResourceEditorGeneral" data-parent="#accordionResourceEditor">
              <div class="card-body">
                <input type="hidden" id="resource_edit_id" value="0">
					  <input type="hidden" id="resource_edit_guid" value="0">
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <input type="text" id="resource_edit_name" class="form-control" placeholder="Give this resource a name">
                      <label for="resource_edit_name" class="active">Name</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <select class="mdb-select dropdown-primary" id="resource_edit_parent" style="font-size: 0.8em">
									</select>
                      <label for="resource_edit_parent" class="active">Group</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <input type="text" id="resource_edit_company" class="form-control" placeholder="Enter a business name">
                      <label for="resource_edit_company" class="active">Company</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <input type="text" id="resource_edit_notes" class="form-control" placeholder="Enter some notes about this resource">
                      <label for="resource_edit_notes" class="active">Notes</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <p>Click on 'upload' to add an image to this resource item</p>
                    <img id="resource_edit_image_url" class="display-resource-image" style="width: 60px;">
                    <input type="file" id="file_handler_resource_image" style="display: none" onchange="setResourceImage()" accept=".png, .jpg, .jpeg">
                    <button class="set-resource-image" style="margin-left: 50px">Upload</a>
                    </div>
					</div>
              </div>
            </div>
          </div>
          <div class="card" id="ResourceEditorAvailability">
            <a class="card-header collapsed" id="card-header-availability-section" role="tab" data-toggle="collapse" data-parent="#accordionResourceEditor" href="#collapseResourceEditorAvailability" aria-expanded="false" aria-controls="collapseResourceEditorAvailability">
              <p class="mb-0">
                <img class="header-icon" src="img/svg/calendar-edit.svg"> Availability <i class="fas fa-angle-down rotate-icon"></i>
                <img id="ResourceEditorAvailabilityLocked" src="img/svg/lock-closed.svg" class="editor-locked" style="display: none;">
              </p>
            </a>
            <div id="collapseResourceEditorAvailability" class="accordion-item-ui collapse" role="tabpanel" aria-labelledby="headingResourceEditorAvailability" data-parent="#accordionResourceEditor">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <select class="mdb-select dropdown-primary" id="resource_edit_calendar_id" style="font-size: 0.8em">
									</select>
                      <label for="resource_edit_calendar_id">Calendar</label>
                    </div>
                    <a class="small new-calendar">Create a Resource Calendar?</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card" id="ResourceEditorAllocation">
            <a class="card-header collapsed" id="card-header-allocation-section" role="tab" data-toggle="collapse" data-parent="#accordionResourceEditor" href="#collapseResourceEditorAllocation" aria-expanded="false" aria-controls="collapseResourceEditorAllocation">
              <p class="mb-0">
                <img class="header-icon" src="img/svg/allocation.svg"> Allocation <i class="fas fa-angle-down rotate-icon"></i>
                <img id="ResourceEditorAllocationLocked" src="img/svg/lock-closed.svg" class="editor-locked" style="display: none;">
              </p>
            </a>
            <div id="collapseResourceEditorAllocation" class="accordion-item-ui collapse" role="tabpanel" aria-labelledby="headingResourceEditorAllocation" data-parent="#accordionResourceEditor">
              <div class="card-body">
                This Resource has been allocated to:

                <table style="margin-top: 20px" id="table_resource_linked_tasks" class="table">
                  <tbody>
                    <!--<tr id="">
                          <td><span>Human</span></td>
                          <td><input class="form-check-input " type="checkbox" id="resource_group_contains_humans">
                            <label class="form-check-label" for="resource_group_contains_humans" class="label-table"></label></td>
                        </tr>-->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="card" id="ResourceEditorFinancial" style="border: none !important;">
            <a class="card-header collapsed" id="card-header-financial-section" role="tab" data-toggle="collapse" data-parent="#accordionResourceEditor" href="#collapseResourceEditorFinancial" aria-expanded="false" aria-controls="collapseResourceEditorFinancial">
              <p class="mb-0">
                <img class="header-icon" src="img/svg/pound-sign-circle.svg"> Financial <i class="fas fa-angle-down rotate-icon"></i>
                <img id="ResourceEditorFinancialLocked" src="img/svg/lock-closed.svg" class="editor-locked" style="display: none;">
              </p>
            </a>
            <div id="collapseResourceEditorFinancial" class="accordion-item-ui collapse" role="tabpanel" aria-labelledby="headingResourceEditorFinancial" data-parent="#accordionResourceEditor">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <input type="text" id="resource_edit_cost_rate" class="form-control" placeholder="Enter the cost">
                      <label for="resource_edit_cost_rate" class="active">Cost £</label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="md-form">
                      <select class="mdb-select dropdown-primary" id="resource_edit_unit_of_measure">
                        <!-- Quantity"> -->
                        <option value="1">Number (no)</option>
                          <option value="2">Item (item)</option>
                        <!-- Time -->
                          <option value="3">Minute (min)</option>
                          <option value="4">Hour (hr)</option>
                          <option value="5">Day (day)</option>
                          <option value="6">Week (wk)</option>
                          <option value="7">Month (mo)</option>
                        <!-- Linear -->
                          <option value="8">Milimeter (mm)</option>
                          <option value="9">Meter (m)</option>
                          <option value="10">Kilometer (km)</option>
                           <!-- Area -->
                          <option value="11">Square Meter (m2)</option>
                             <option value="12">Square Kilometer (km2)</option>
                        <!-- Weight -->
                          <option value="13">Kilogram (kg)</option>
                             <option value="14">Tonne (t)</option>
                        <!-- Volume -->
                          <option value="15">Cubic Meter (m3)</option>
                             <option value="16">Litre (l)</option>
					</select>
                      <label for="resource_edit_cost_rate" class="active">Unit of measure</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="mr-auto delete-resource" style="">Delete</button>
        <button class="save-resource">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal animated fadeIn" id="modal_resource_groups_editor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 1998">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
                       <h4 class="modal-title">Resource Groups Editor</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="accordion md-accordion" id="accordionResourceGroupsEditor" role="tablist" aria-multiselectable="true">
          <div class="card">
            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionResourceGroupsEditor" href="#collapseResourceGroupsEditorGeneral" aria-expanded="false" aria-controls="collapseResourceGroupsEditorGeneral">
              <p class="mb-0">
                <img class="header-icon" src="img/svg/general.svg"> General<i class="fas fa-angle-down rotate-icon"></i>
              </p>
            </a>
            <div id="collapseResourceGroupsEditorGeneral" class="accordion-item-ui" role="tabpanel" aria-labelledby="headingResourceGroupsEditorGeneral" data-parent="#accordionResourceGroupsEditor">
              <input type="hidden" id="resource_group_id_local" value="0">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <input type="text" id="resource_group_new_name" class="form-control" placeholder="Type here">
                      <label for="resource_group_new_name" class="active">Group name</label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <select class="mdb-select dropdown-primary" id="resource_group_calendar_id">
								 
					</select>
                      <label for="resource_group_calendar_id" class="active">Calendar</label>
                    </div>
                  </div>
                </div>


                <!--
                <div class="row">
                  <div class="col">
                    <p>Does this group contain <strong>human</strong> Resources?<br>e.g. Engineer or Driver</p>
                    <table style="width: 50%" class="table table-sm">
                      <tbody>
                        <tr id="">
                          <td><span>Human</span></td>
                          <td><input class="form-check-input " type="checkbox" id="resource_group_contains_humans">
                            <label class="form-check-label" for="resource_group_contains_humans" class="label-table"></label></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <p>Does this group contain <strong>consumable</strong> Resources?<br>e.g. Asphalt or Timber</p>
                    <table style="width: 50%" class="table table-sm">
                      <tbody>
                        <tr id="">
                          <td><span>Consumables</span></td>
                          <td><input class="form-check-input " type="checkbox" id="resource_group_contains_consumables">
                            <label class="form-check-label" for="resource_group_contains_consumables" class="label-table"></label></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
-->
              </div>
            </div>
          </div>

          <div class="card">
            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionResourceGroupsEditor" href="#collapseResourceGroupsEditorOutputs" aria-expanded="false" aria-controls="collapseResourceGroupsEditorOutputs">
              <p class="mb-0">
                <img class="header-icon" src="img/svg/shovel.svg"> Outputs<i class="fas fa-angle-down rotate-icon"></i>
              </p>
            </a>
            <div id="collapseResourceGroupsEditorOutputs" class="accordion-item-ui collapse" role="tabpanel" aria-labelledby="headingResourceGroupsEditorOutputs" data-parent="#accordionResourceGroupsEditor">

              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <input type="text" id="resource_group_min_output_value" class="form-control" placeholder="Type here">
                      <label for="resource_group_min_output_value" class="active">Min output value</label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="md-form">

                      <input type="text" id="resource_group_max_output_value" class="form-control" placeholder="Type here">
                      <label for="resource_group_max_output_value" class="active">Max output value</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <select class="mdb-select dropdown-primary" id="resource_group_outputs_unit">
                        <!-- Quantity"> -->
                        <option></option>
								
								     <option value="hr">Hour (hr)</option>
                          <option value="day">Day (day)</option>
                          <option value="week">Week (wk)</option>
                          <option value="month">Month (mo)</option>
								  
                          <option value="item">Item (item)</option>
                          <option value="no">Number (no)</option>
								  
                          <option value="mm">Milimeter (mm)</option>
                          <option value="m">Meter (m)</option>
                          <option value="km">Kilometer (km)</option>
                           <!-- Area -->
                          <option value="m2">Square Meter (m2)</option>
                             <option value="km2">Square Kilometer (km2)</option>
                        <!-- Weight -->
                          <option value="kg">Kilogram (kg)</option>
                             <option value="t">Tonne (t)</option>
                        <!-- Volume -->
                          <option value="m3">Cubic Meter (m3)</option>
                             <option value="l">Litre (l)</option>
					</select>
                      <label for="resource_edit_outputs_period" class="active">Unit of measure</label>
                    </div>
                  </div>
                  <div class="col col-outputs-period">
                    <div class="md-form">
                      <select class="mdb-select dropdown-primary" id="resource_group_outputs_period">
                           <!-- Time -->
                          <option></option>
                          <option value="1">Hour (hr)</option>
                          <option value="2">Day (day)</option>
                          <option value="3">Week (wk)</option>
                          <option value="4">Month (mo)</option>
								  <!-- Linear -->
								
					</select>
                      <label for="resource_group_outputs_period" class="active">Per (time period)</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button class="mr-auto delete-resource-group">Delete</button>
        <button data-dismiss="modal" class="add-resource-group">Save</button>
      </div>
    </div>
  </div>
</div>



<div class="modal animated fadeIn" id="modal_resource_groups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 1998">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
                       <h4 class="modal-title">Resource Groups Editor</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <p>Edit your existing groups</p>
            <table id="table_resource_groups" class="table table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                </tr>
              </thead>
              <tbody>
                <tr data-index="name">
                  <td><span>Name</span></td>
                  <td><input class="form-check-input " type="checkbox" id="resource_column_name">
                    <label class="form-check-label" for="resource_column_name" class="label-table"></label></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button data-dismiss="modal" class="add-resource-group">Save</button>
      </div>
    </div>
  </div>
</div>




<div class="modal animated fadeIn" id="modal_edit_resource_columns" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
     <div class="modal-header">
                       <h4 class="modal-title">Resources Columns Editor</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <p>Choose which columns to show in the resource pane</p>
            <table id="table_resource_columns" class="table table-sm">
              <tbody>
                <tr data-index="name">
                  <td><span>Name</span></td>
                  <td><input class="form-check-input " type="checkbox" id="resource_column_name">
                    <label class="form-check-label" for="resource_column_name" class="label-table"></label></td>
                </tr>
                <tr data-index="resource_calendar">
                  <td><span>Calendar</span></td>
                  <td><input class="form-check-input " type="checkbox" id="resource_column_calendar">
                    <label class="form-check-label" for="resource_column_calendar" class="label-table"></label></td>
                </tr>
                <tr data-index="company">
                  <td><span>Company</span></td>
                  <td><input class="form-check-input " type="checkbox" id="resource_column_company">
                    <label class="form-check-label" for="resource_column_company" class="label-table"></label></td>
                </tr>
                <tr data-index="notes">
                  <td><span>Notes</span></td>
                  <td><input class="form-check-input " type="checkbox" id="resource_column_notes">
                    <label class="form-check-label" for="resource_column_notes" class="label-table"></label></td>
                </tr>
                <tr data-index="cost_rate">
                  <td><span>Cost rate</span></td>
                  <td><input class="form-check-input " type="checkbox" id="resource_column_cost_rate">
                    <label class="form-check-label" for="resource_column_cost_rate" class="label-table"></label></td>
                </tr>
                <tr data-index="resource_image">
                  <td><span>Image</span></td>
                  <td><input class="form-check-input " type="checkbox" id="resource_column_resource_image">
                    <label class="form-check-label" for="resource_column_resource_image" class="label-table"></label></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="save-resource-columns">Save</button>
      </div>
    </div>
  </div>
</div>