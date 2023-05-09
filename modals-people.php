<div class="modal animated fadeIn" id="modal_edit_profile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Profile Editor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="accordion md-accordion" id="accordionProfileEditor" role="tablist" aria-multiselectable="true">
          <div class="card">
            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionProfileEditor" href="#collapseProfileEditorContact" aria-expanded="false" aria-controls="collapseResourceEditorContact">
              <p class="mb-0">Contact Details
              </p>
            </a>
            <div id="collapseProfileEditorContact" class="collapse" role="tabpanel" aria-labelledby="headingProfileEditorContact" data-parent="#accordionProfileEditor">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <input type="text" id="profile_first_name" class="form-control" value="<?=$_SESSION['user']['first_name']?>">
                      <label for="profile_first_name" class="active">First Name</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <input type="text" id="profile_last_name" class="form-control" value="<?=$_SESSION['user']['last_name']?>">
                      <label for="profile_last_name" class="active">Last Name</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <input type="text" id="email_address" class="form-control" value="<?=$_SESSION['user']['email_address']?>">
                      <label for="profile_email_address" class="active">Email Address</label>
                    </div>
                  </div>
                </div>
					 
                <div class="row">
                  <div class="col">
                    <div class="md-form">
                      <input type="text" id="profile_telephone_number" class="form-control" value="<?=$_SESSION['user']['telephone_number']?>">
                      <label for="profile_telephone_number" class="active">Phone Number</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionProfileEditor" href="#collapseProfileEditorImage" aria-expanded="false" aria-controls="collapseResourceEditorImage">
              <p class="mb-0">Profile Image
              </p>
            </a>
            <div id="collapseProfileEditorImage" class="collapse" role="tabpanel" aria-labelledby="headingProfileEditorImage" data-parent="#accordionProfileImage">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <p>This is your profile image - click on 'upload' to replace it!</p>
                    <img class="display-profile-image" src="<?=$_SESSION['user']['avatar_url']?>" style="height: 60px;">
                    <input type="file" id="file_handler_profile_ui" style="display: none" onchange="setProfileImageUI()"  accept=".png, .jpg, .jpeg">
                    <button class="set-profile-image-ui" style="margin-left: 50px">Upload</a>
                    </div>
					</div>

                  </div>
                </div>
              </div>
          <div class="card">
            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionProfileEditor" href="#collapseProfileEditorBackground" aria-expanded="false" aria-controls="collapseResourceEditorBackground">
              <p class="mb-0">
                Background Image
              </p>
            </a>
            <div id="collapseProfileEditorBackground" class="collapse" role="tabpanel" aria-labelledby="headingProfileEditorBackground" data-parent="#accordionProfileBackground">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <p class="helper">Upload your background, then set its opacity below</p>
                    <img class="display-background-image" src="<?=$_SESSION['user']['background_url']?>" style="width: 200px; opacity: <?=$_SESSION['user']['background_opacity']?>">
                    <div style="color: <?=$_SESSION['user']['opacity_font']?>" id="background-example-color">Ensure that you<br>can read this<br>example text</be></div>
                    <input type="file" id="file_handler_background" style="display: none" onchange="setBackgroundImage()" accept=".png, .jpg, .jpeg">
                    <button class="set-background-image" style="margin-left: 50px">Upload</button>
                    </div>
					</div><br>
                <div class="row">
                  <div class="col">
                    <p>Background opacity</p>
                    <div class="md-form">
                      <button id="opacity-2">2</button>
                      <button id="opacity-3">3</button>
                      <button id="opacity-4">4</button>
                      <button id="opacity-5">5</button>
                      <button id="opacity-6">6</button>
                      <button id="opacity-7">7</button>
                      <button id="opacity-8">8</button>
                      <button id="opacity-9">9</button>
                      
                      <input type="hidden" value="" id="background_opacity">
                    </div>
                    </div>
					</div>
                <div class="row">
                  <div class="col">
                    <p>Font colour</p>
                    <div class="md-form">
                      <button id="opacity-font-default">Default</button>
                      <button id="opacity-font-white">White</button>
                      <button id="opacity-font-yellow">Yellow</button>
                      <button id="opacity-font-blue" style="width: 80px; margin-right: -12px; padding: 10px 0; border-radius: 0 !important;">Blue</button>
                      <button id="opacity-font-red" style="width: 80px; margin-right: -12px; padding: 10px 0; border-radius: 0 4px 4px 0 !important;">Red</button>
                      <input type="hidden" value="" id="opacity_font">
                    </div>
                    </div>
					</div>
                  </div>
                </div>
              </div>
           <div class="card">
            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionProfileEditor" href="#collapseProfileEditorTeams" aria-expanded="false" aria-controls="collapseResourceEditorTeams">
              <p class="mb-0">Teams
              </p>
            </a>
            <div id="collapseProfileEditorTeams" class="collapse" role="tabpanel" aria-labelledby="headingProfileEditorTeams" data-parent="#accordionProfileEditor">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <p>These are your teams:</p>
                    <div class="profile-teams">
                      <?=$self_user_groups?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <!--
          <div class="card" style="border: none !important;">
            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionProfileEditor" href="#collapseProfileEditorPermissions" aria-expanded="false" aria-controls="collapseResourceEditorTeams">
              <p class="mb-0">
                Permissions
              </p>
            </a>
            <div id="collapseProfileEditorPermissions" class="collapse" role="tabpanel" aria-labelledby="headingProfileEditorPermissions" data-parent="#accordionProfileEditor">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <div id="permission-to-manage-task-editor-general" style="display: none;">You may mange a task's 'General' section
                    <ul>
                      <li>Type</li>
                      <li>Calendar</li>
                      <li>Name</li>
                      <li>Start date</li>
                      <li>Start time</li>
                      <li>Duration</li>
                      <li>Bar colour</li>
                      </ul>
                    </div>
                    <div id="permission-to-view-task-editor-general" style="display: none;">You may view a task's 'General' section
                    <ul>
                      <li>Type</li>
                      <li>Calendar</li>
                      <li>Name</li>
                      <li>Start date</li>
                      <li>Start time</li>
                      <li>Duration</li>
                      <li>Bar colour</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        -->
          </div>
        </div>
              <div class="modal-footer">
                <button class="save-profile">Save</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal animated fadeIn" id="modal_manage_team" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-info" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Collaboration Editor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
                  </div>
                  <div class="modal-body">
                    <div class="accordion md-accordion" id="accordionTeamsEditor" role="tablist" aria-multiselectable="true">
                      <div class="card" id="accordionTeamsEditor-Teams">
                        <div id="collapseTeamsEditorTeams" class="collapse show" role="tabpanel" aria-labelledby="headingTeamsEditorTeams" data-parent="#accordionTeamsEditor">
                          <div class="card-body">
                            <div class="row">
                              <div class="col">
                                <p>Add a new team (e.g. Carpenters) </p>
                                <div class="md-form input-group" style="">
                                  <input type="text" id="new_user_group_name" class="form-control col-12" placeholder="Team name">
                                  <div class="input-group-append">
                                    <span class="input-group-text add-user-group" style="font-size: 0.8em">Add</span>
                                  </div>
                                </div>
                                <p>These are your existing teams:</p>
                                <table id="table_groups" class="table table-sm">
                                  <tbody>
                                    <?php
								 foreach ($user_groups as $user_group)
								 {
									 ?>
                                      <tr>
                                        <td style="width: 80%; border-top: 0px !important">
                                          <?=$user_group['name']?>
                                        </td>
                                        <td style="width: 20%; border-top: 0px !important">
                                          <?php if(isset($user_group[' id '])) { ?>
                                            <div class='' style='width: 20px; float: left' data-index='<?=$user_group[' id ']?>'></div>
                                            <div class='delete-group' style='width: 20px; float: left' data-index='<?=$user_group[' id ']?>'><img class="action-icons" src="img/svg/bin-1.svg"></div>
                                          <?php } ?>
                                        </td>
                                      </tr>
                                      <?php
							 }
							 ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card" id="accordionTeamsEditor-Permissions">
                        <div id="collapseTeamsEditorPermissions" class="collapse" role="tabpanel" aria-labelledby="headingTeamsEditorPermissions" data-parent="#accordionTeamsEditor">
                          <div class="card-body">
                            <div class="row">
                              <div class="col">
                                <p>Configure the account permissions for each of your teams</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card" id="accordionTeamsEditor-People" style="border-bottom: none !important;">
                        <div id="collapseTeamsEditorPeople" class="" role="tabpanel" aria-labelledby="headingTeamsEditorPeople" data-parent="#accordionTeamsEditor">
                          <div class="card-body">
                            <div class="row">
                              <div class="col">
                                <p>Send an <strong>invite email</strong></p>
                                <p>Invite people to join your Ibex account as team members<br>(enter one email address at a time)</p>
                                <div class="md-form input-group" >
                                  <input type="text" id="new_user_add_email_address" class="form-control col-12" placeholder="someone@example.com">
                                  <div class="input-group-append">
                                    <span class="input-group-text add-new-user" style="font-size: 0.8em">Next</span>
                                  </div>
                                </div>
                                <p>These are your current team members:</p>
                                <table id="table_group_members_ui" style="width: inherit;">
                                </table>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button class="close-teams-modal" data-dismiss="modal">Save</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal animated fadeIn" id="modal_view_user_groups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-info" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Team Member Editor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" id="team_member_id" class="form-control" value="">
                    <input type="hidden" id="team_member_new" class="form-control" value="false">
                    <div class="card">
                      <div class="card-body">
                        <p>Please enter the invitee's names, then assign the team/s</p>
                        <div class="row">
                          <div class="col">
                            <div class="md-form">
                              <input type="text" id="team_member_first_name" class="form-control" value="">
                              <label for="team_member_first_name" class="active">First name</label>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <div class="md-form">
                              <input type="text" id="team_member_last_name" class="form-control" value="">
                              <label for="team_member_last_name" class="active">Last name</label>
                            </div>
                          </div>
                        </div>

                        <div class="row" style="display: none;">
                          <div class="col">
                            <div class="md-form">
                              <input type="text" id="team_member_email_address" disabled class="form-control" value="">
                              <label for="team_member_email_address" class="active">Email address</label>
                            </div>
                          </div>
                        </div>


                        <hr>
                        <div class="row">
                          <div class="col">
                            <div class="md-form">
                              <select id="team_member_groups" class='form-control user-group-links' multiple='multiple' style='width: 100%'></select>
                              <label for="team_member_groups" class="active">Team/s</label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button class="mr-auto delete-team-member">Delete</button>
                    <button class="save-team-member">Save</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal animated fadeIn" id="modal_invite_team" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Invite People</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
                  </div>
                  <div class="modal-body">
                    <div class="tab-content card card-task-editor" style="padding-bottom: 20px;">
                      <div class="row">
                        <div class="col-md-12" style="text-align: center">
                          <p>Bring your team members onboard - simply add them below</p>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="md-form input-group" style="">
                            <input type="text" id="team_member_invite_email_address" class="form-control col-12" placeholder="someone@example.com">
                            <div class="input-group-append">
                              <span class="input-group-text add-user-group" style="font-size: 0.8em">Add</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 team-members-invite-container">
                          <div class="chip chip-team">
                            <?=$_SESSION['user']['email_address']?> (You)</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button class="skip-team-batch">Skip</button>
                    <button class="add-team-batch">Invite</button>
                  </div>
                </div>
              </div>
            </div>







            <div class="modal animated fadeIn" id="modal_permissions" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-info" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title modal-title-permissions">Permissions for <strong></strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
                  </div>
                  <div class="modal-body">

                    <input id="modal_permissions_group_id" type="hidden">
                    <div class="accordion md-accordion" id="accordionPermissionsEditor" role="tablist" aria-multiselectable="true">
                      <div class="card">
                        <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionPermissionsEditor" href="#collapsePermissionsEditorScheduling" aria-expanded="false" aria-controls="collapsePermissionsEditorScheduling">
                          <p class="mb-0">
                            Scheduling
                          </p>
                        </a>
                        <div id="collapsePermissionsEditorScheduling" class="collapse" role="tabpanel" aria-labelledby="headingPermissionsEditorScheduling" data-parent="#accordionPermissionsEditor">
                          <div class="card-body">
                            <div class="row">
                              <div class="col">
                                <p>Direct access to the following Task Editor sections and indirectly throughout this platform (e.g. Gantt interface and Resources page)</p>
                                <p>None = team has no access<br>View = team may only view the content<br>Manage = team has full control</p>
                                <div class="md-form">
                                  <table id="table_permissions_scheduling" class="table table-sm">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <!--
                                                <th class="ibex-tooltip" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="This group will <b>not<b> have <br>any access">None</th>
                                                <th class="ibex-tooltip" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="This group will have <br><b>view-only<b> access">View</th>
                                                <th class="ibex-tooltip" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="This group may <br><b>Add</b>/ <b>Edit</b>/ <b>Delete</b><br> as applicable">Manage</th>
                                                -->
                                        <th>None</th>
                                        <th>View</th>
                                        <th>Manage</th>

                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr data-index="scheduling_general">
                                        <td><span>General</span></td>



                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="scheduling_general_0" data-value="0">
                                          <!--<label class="form-check-label" for="scheduling_general_0"></label>-->
                                        </td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="scheduling_general_1" data-value="1"><label class="form-check-label" for="scheduling_general_1"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="scheduling_general_2" data-value="2"><label class="form-check-label" for="scheduling_general_2"></label></td>



                                      </tr>
                                      <tr>
                                        <td><span>Workload</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="scheduling_workload_0" data-value="0"><label class="form-check-label" for="scheduling_workload_0"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="scheduling_workload_1" data-value="1"><label class="form-check-label" for="scheduling_workload_1"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="scheduling_workload_2" data-value="2"><label class="form-check-label" for="scheduling_workload_2"></label></td>
                                      </tr>
                                      <tr>
                                        <td><span>Calendars</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="scheduling_calendars_0" data-value="0"><label class="form-check-label" for="scheduling_calendars_0"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="scheduling_calendars_1" data-value="1"><label class="form-check-label" for="scheduling_calendars_1"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="scheduling_calendars_2" data-value="2"><label class="form-check-label" for="scheduling_calendars_2"></label></td>
                                      </tr>
                                      <tr>
                                        <td><span>Dependencies</span></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="scheduling_dependencies_0" data-value="0">
                                        </td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="scheduling_dependencies_1" data-value="1"><label class="form-check-label" for="scheduling_dependencies_1"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="scheduling_dependencies_2" data-value="2"><label class="form-check-label" for="scheduling_dependencies_2"></label></td>

                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionPermissionsEditor" href="#collapsePermissionsEditorResoources" aria-expanded="false" aria-controls="collapsePermissionsEditorResoources">
                          <p class="mb-0">
                            Resources
                          </p>
                        </a>
                        <div id="collapsePermissionsEditorResoources" class="collapse" role="tabpanel" aria-labelledby="headingPermissionsEditorResoources" data-parent="#accordionPermissionsEditor">
                          <div class="card-body">
                            <div class="row">
                              <div class="col">
                                <p>Direct access to the following Resource Editor sections and indirectly throughout this platform (e.g. Resources interface)</p>
                                <div class="md-form">
                                  <table id="table_permissions_resources" class="table table-sm">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <!--
                                                <th class="ibex-tooltip" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="This group will <b>not<b> have <br>any access">None</th>
                                                <th class="ibex-tooltip" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="This group will have <br><b>view-only<b> access">View</th>
                                                <th class="ibex-tooltip" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="This group may <br><b>Add</b>/ <b>Edit</b>/ <b>Delete</b><br> as applicable">Manage</th>
                                                -->
                                        <th>None</th>
                                        <th>View</th>
                                        <th>Manage</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td><span>General</span></td>

                                        <td id="permissions_resources_general_0"><input class="form-check-input check-permission-groups" type="checkbox" id="resources_general_0" data-value="0"><label class="form-check-label" for="resources_general_0"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_general_1" data-value="1"><label class="form-check-label" for="resources_general_1"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_general_2" data-value="2"><label class="form-check-label" for="resources_general_2"></label></td>

                                      </tr>
                                      <tr>
                                        <td><span>Availability</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_availability_0" data-value="0"><label class="form-check-label" for="resources_availability_0"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_availability_1" data-value="1"><label class="form-check-label" for="resources_availability_1"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_availability_2" data-value="2"><label class="form-check-label" for="resources_availability_2"></label></td>
                                      </tr>
                                      <tr>
                                        <td><span>Allocation</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_allocation_0" data-value="0"><label class="form-check-label" for="resources_allocation_0"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_allocation_1" data-value="1"><label class="form-check-label" for="resources_allocation_1"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_allocation_2" data-value="2"><label class="form-check-label" for="resources_allocation_2"></label></td>
                                      </tr>
                                      <tr>
                                        <td><span>Financial</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_financial_0" data-value="0"><label class="form-check-label" for="resources_financial_0"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_financial_1" data-value="1"><label class="form-check-label" for="resources_financial_1"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_financial_2" data-value="2"><label class="form-check-label" for="resources_financial_2"></label></td>
                                      </tr>
                                      <tr>
                                        <td><span>Groups</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_groups_0" data-value="0"><label class="form-check-label" for="resources_groups_0"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_groups_1" data-value="1"><label class="form-check-label" for="resources_groups_1"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_groups_2" data-value="2"><label class="form-check-label" for="resources_groups_2"></label></td>
                                      </tr>
                                      <tr>
                                        <td><span>Calendars</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_calendars_0" data-value="0"><label class="form-check-label" for="resources_calendars_0"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_calendars_1" data-value="1"><label class="form-check-label" for="resources_calendars_1"></label></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="resources_calendars_2" data-value="2"><label class="form-check-label" for="resources_calendars_2"></label></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionPermissionsEditor" href="#collapsePermissionsEditorCollaboration" aria-expanded="false" aria-controls="collapsePermissionsEditorCollaboration">
                          <p class="mb-0">Collaboration
                          </p>
                        </a>
                        <div id="collapsePermissionsEditorCollaboration" class="collapse" role="tabpanel" aria-labelledby="headingPermissionsEditorCollaboration" data-parent="#accordionPermissionsEditor">
                          <div class="card-body">
                            <div class="row">
                              <div class="col">
                                <p>Direct access to other team members via the messaging interface.</p>
                                <p>Commenting on all activities via the activity feed and the task editor.</p>
                                <p>Uploading, downloading and attaching files to tasks.</p>
                                <p>Managing your team members (existing and invited).</p>
                                <div class="md-form">
                                  <table id="table_permissions_collaboration" class="table table-sm">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <!--
                                                <th class="ibex-tooltip" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="This group will <b>not<b> have <br>any access">None</th>
                                                <th class="ibex-tooltip" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="This group will have <br><b>view-only<b> access">View</th>
                                                <th class="ibex-tooltip" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="This group may <br><b>Add</b>/ <b>Edit</b>/ <b>Delete</b><br> as applicable">Manage</th>
                                                -->
                                        <th>None</th>
                                        <th>View</th>
                                        <th>Manage</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td><span>Team Members</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="collaboration_team_members_0" data-value="0"><label class="form-check-label" for="collaboration_team_members_0"></label></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="collaboration_team_members_1" data-value="1"></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="collaboration_team_members_2" data-value="2"><label class="form-check-label" for="collaboration_team_members_2"></label></td>
                                      </tr>
                                      <tr>
                                        <td><span>Messaging</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="collaboration_messaging_0" data-value="0"><label class="form-check-label" for="collaboration_messaging_0"></label></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="collaboration_messaging_1" data-value="1"</td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="collaboration_messaging_2" data-value="2"><label class="form-check-label" for="collaboration_messaging_2"></label></td>
                                      </tr>
                                      <tr>
                                        <td><span>Commenting</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="collaboration_commenting_0" data-value="0"><label class="form-check-label" for="collaboration_commenting_0"></label></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="collaboration_commenting_1" data-value="1"><label class="form-check-label" for="collaboration_commenting_1"></label></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="collaboration_commenting_2" data-value="2"><label class="form-check-label" for="collaboration_commenting_2"></label></td>
                                      </tr>
                                      <tr>
                                        <td><span>Files</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="collaboration_files_0" data-value="0"><label class="form-check-label" for="collaboration_files_0"></label></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="collaboration_files_1" data-value="1"><label class="form-check-label" for="collaboration_files_1"></label></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="collaboration_files_2" data-value="2"><label class="form-check-label" for="collaboration_files_2"></label></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionPermissionsEditor" href="#collapsePermissionsEditorVersion" aria-expanded="false" aria-controls="collapsePermissionsEditorVersion">
                          <p class="mb-0">
                            Version Control
                          </p>
                        </a>
                        <div id="collapsePermissionsEditorVersion" class="collapse" role="tabpanel" aria-labelledby="headingPermissionsEditorVersion" data-parent="#accordionPermissionsEditor">
                          <div class="card-body">
                            <div class="row">
                              <div class="col">
                                <p>Direct access to delete your project data (scheduling, resources, files & activity) which cannot be restored.</p>
                                <p>Baselines of your project or individual tasks as benchmarks for comparing your intentions with the actual outcomes.</p>
                                <p>Rolling-back of your scheduling activities to an earlier or later point in time, subject to 'forking' of the timeline.</p>
                                <div class="md-form">
                                  <table id="table_permissions_version_control" class="table table-sm">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <th>View</th>
                                        <th>Manage</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td><span>Project Data</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="version_control_project_data_1" data-value="1"><label class="form-check-label" for="version_control_project_data_1"></label></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="version_control_project_data_2" data-value="2"><label class="form-check-label" for="version_control_project_data_2"></label></td>
                                      </tr>
                                      <tr>
                                        <td><span>Demo Project Data</span></td>

                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="version_control_demo_project_data_1" data-value="1"><label class="form-check-label" for="version_control_demo_project_data_1"></label></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="version_control_demo_project_data_2" data-value="2"><label class="form-check-label" for="version_control_demo_project_data_2"></label></td>
                                      </tr>
                                      <tr>
                                        <td><span>Baselines</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="version_control_baselines_1" data-value="1"><label class="form-check-label" for="version_control_baselines_1"></label></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="version_control_baselines_2" data-value="2"><label class="form-check-label" for="version_control_baselines_2"></label></td>
                                      </tr>
                                      <tr>
                                        <td><span>Rollback</span></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="version_control_rollback_1" data-value="1"><label class="form-check-label" for="version_control_rollback_1"></label></td>
                                        <td><input class="form-check-input check-permission-groups" type="checkbox" id="version_control_rollback_2" data-value="2"><label class="form-check-label" for="version_control_rollback_2"></label></td>
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
                  <div class="modal-footer">
                    <button type="button" class="save-permission-groups">Save</button>
                  </div>
                </div>
              </div>
            </div>