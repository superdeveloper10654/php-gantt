<div role="tabpanel" class="tab-pane animated fadeIn tab-pane-settings" id="settings" aria-labelledby="settings-tab">
  <div id="setup-scheduling-wrapper" style="padding: 20px; border-bottom: solid 1px #ddd; display: none;">
    <div class="col-md-7" style="height: auto; float: left; text-align: right;">
      <h3>Scheduling Tweaks</h3>
      <p>Make your changes below then click Continue</p>
    </div>
    <div class="col-md-3" style="height: auto; padding-top: 10px;">
      <button class="mx-auto btn-green continue_to_work_env">Continue</button>
    </div>
  </div>
  <div id="setup-collaboration-wrapper" style="padding: 20px; border-bottom: solid 1px #ddd; display: none;">
    <div class="col-md-7" style="height: auto; float: left; text-align: right;">
      <h3>Collaboration Settings</h3>
      <p>Let's bring other people to your account</p>
    </div>
    <div class="col-md-3" style="height: auto; padding-top: 10px;">
      <button class="mx-auto btn-green continue_to_finish">Continue</button>
    </div>
  </div>
  <div class="row row-settings">
    <div class="col">
      <div class="accordion md-accordion" id="accordionSettingsTab" role="tablist" aria-multiselectable="true">
        <div class="card" id="SettingsTabScheduling">
          <a class="card-header" role="tab" data-toggle="collapse" data-parent="#accordionSettingsTab" href="#collapseSettingsTabScheduling" aria-expanded="false" aria-controls="collapseSettingsTabScheduling">
            <p class="mb-0">
              <img class="header-icon" src="img/svg/settings.svg"> Scheduling<i class="fas fa-angle-down rotate-icon"></i>
            </p>
          </a>
          <div id="collapseSettingsTabScheduling" class="" role="tabpanel" aria-labelledby="headingSettingsTabScheduling" data-parent="#accordionSettingsTab">
            <div class="card-body" style="">
              <h4>Calendars</h4>
              <p>Manage the calendars (availabity) of your tasks and resources</p>
              <div class="row">
                <div class="col huge mx-auto" id="settings-edit-calendars" data-toggle="modal" data-target="#modal_edit_calendars" style="margin-left: 20px !important;">
                  <img class="settings " src="img/svg/calendar.svg">
                  <h4>Edit calendars</h4>
                  <p>Adjust your existing calendars</p>
                </div>
                <div class="col huge add-task-calendar">
                  <img class="settings " src="img/svg/calendar.svg">
                  <h4>Task calendar</h4>
                  <p>Add a calendar for your tasks</p>
                </div>
                <div class="col huge add-resource-calendar">
                  <img class="settings " src="img/svg/calendar.svg">
                  <h4>Resource calendar</h4>
                  <p>Add a calendar for your resources</p>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="" style="margin: 10px;" id="">
                  <h4>Duration unit</h4>
                  <p>Select your normal working pattern</p>
                  <button id="" class="timing-unit" data-index="1" style="margin: 10px; width: 200px;">
          <img class="settings " src="img/svg/calendar.svg">
          <h5>Hours</h5>
          <p>For minute-level detail</p>
                                  </button>
                  <button id="" class="active-setting timing-unit" data-index="2" style="margin: 10px; width: 200px;">
          <img class="settings " src="img/svg/calendar.svg">
          <h5>Days</h5>
          <p>For dawn to dusk </p>
                                  </button>
                  <button id="" class="timing-unit" data-index="3" style="margin: 10px; width: 200px;">
          <img class="settings " src="img/svg/calendar.svg">
          <h5>Nights</h5>
          <p>For dusk to dawn</p>
                                  </button>
                  <button id="" class="timing-unit" data-index="4" style="margin: 10px; width: 200px;">
          <img class="settings " src="img/svg/calendar.svg">
          <h5>Shifts</h5>
          <p>for irregular work</p>
                                  </button>
                  <button id="" class="timing-unit" data-index="5" style="margin: 10px; width: 200px;">
          <img class="settings " src="img/svg/calendar.svg">
          <h5>Other</h5>
          <p>For anything else</p>
                                  </button>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="md-form" id="period-descriptors-wrapper" style="display: none">
                    <p>Enter a descriptive word, expressed a singular and plural</p>
                    <div class="col-md-4">
                      <p>Singular</p>
                      <input type="text" id="period_descriptor_singular" class="form-control" placeholder="e.g: 'Session' or 'Class'">
                    </div><br>
                    <div class="col-md-4">
                      <p>Plural</p>
                      <input type="text" id="period_descriptor_plural" class="form-control" placeholder="e.g: 'Sessions' or Classes'">
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col">
                  <div class="md-form">
                    <h4> Placement of new tasks </h4>
                    <p>Select how a new task or milestone show be inserted</p>
                    <button id="" class="active-setting task-placement" data-index="1" style="margin: 10px; width: 250px;">
          <img class="settings " src="img/svg/calendar.svg">
          <h5>Today</h5>
          <p>Use today's date or next available</p>
                                  </button>
                    <button id="" class="task-placement" data-index="2" style="margin: 10px; width: 250px;">
          <img class="settings " src="img/svg/calendar.svg">
          <h5>Earliest</h5>
          <p>Use the earliest date available</p>
                                  </button>
                    <button id="" class="task-placement" data-index="3" style="margin: 10px; width: 250px;">
          <img class="settings " src="img/svg/calendar.svg">
          <h5>Latest</h5>
          <p>Use the latest date available</p>
                                  </button>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row" style="display: none;">
                <div class="col">
                  <div class="md-form" style="padding: 0 0 50px 0;">
                    <h4> Scheduling preference </h4>
                    <p>Select a scheduling option<br>Note that any manually-scheduled tasks will be overriden if you later select auto-scheduling</p>
                    <button id="" class="active-setting auto-scheduler" data-index="1" style="margin: 10px; width: 250px;">
          <img class="settings " src="img/svg/calendar.svg">
          <h5>Auto</h5>
          <p>Let Ibex do your scheduling</p>
                                  </button>
                    <button id="" class="auto-scheduler" data-index="0" style="margin: 10px; width: 250px;">
          <img class="settings " src="img/svg/calendar.svg">
          <h5>Manual</h5>
          <p>Do all the scheduling yourself</p>
                                  </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card" id="SettingsTabCollaboration">
          <a class="card-header" role="tab" data-toggle="collapse" data-parent="#accordionSettingsTab" href="#collapseSettingsTabCollaboration" aria-expanded="false" aria-controls="collapseSettingsTabCollaboration">
            <p class="mb-0">
              <img class="header-icon" src="img/svg/user.svg"> Collaboration<i class="fas fa-angle-down rotate-icon"></i>
            </p>
          </a>
          <div id="collapseSettingsTabCollaboration" class="" role="tabpanel" aria-labelledby="headingSettingsTabTeams" data-parent="#accordionSettingsTab">
            <div class="card-body">
              <h4>Team members</h4>
              <p>Organise your people into teams (i.e. by department, skillset or trade)</p>
              <div class="row">
                <div class="col huge manage-teams">
                  <img class="settings" src="img/svg/calendar.svg">
                  <h4>Manage teams</h4>
                  <p>Add or edit your teams</p>
                </div>
                <div class="col huge manage-people">
                  <img class="settings" src="img/svg/calendar.svg">
                  <h4>Manage people</h4>
                  <p>Invite people to collaborate</p>
                  </button>
                </div>
              </div>
              <hr>
              <p class="mb-0">
                <h4>Permissions</h4>
                <p>Manage your team permissions (access rights)</p>
                <div class="row groups-container"></div>
                <hr>
                <h4>Broadcasts</h4>
                <p>Broadcast a message to your team member when they login</p>
                <div class="row">
                  <div class="col huge manage-broadcasts" data-toggle="modal">
                    <img class="settings " src="img/svg/broadcast.svg">
                    <h4>Broadcasts</h4>
                    <p>Manage your broadcast messages</p>
                  </div>
                </div>
            </div>
            <div class="container-permission-groups-settings"></div>
          </div>
        </div>
      </div>
      <div class="card" id="SettingsTabBilling" style="display: none;">
        <a class="card-header" role="tab" data-toggle="collapse" data-parent="#accordionSettingsTab" href="#collapseSettingsTabBilling" aria-expanded="false" aria-controls="collapseSettingsTabScheduling">
          <p class="mb-0">
            <img class="header-icon" src="img/svg/pound-sign-circle.svg"> Billing & Payments<i class="fas fa-angle-down rotate-icon"></i>
          </p>
        </a>
        <div id="collapseSettingsTabBilling" class="collapse" role="tabpanel" aria-labelledby="headingSettingsTabBilling" data-parent="#accordionSettingsTab">
          <div class="card-body" style="padding: 10px 60px">
            <div class="row">
              <div class="col">
                <div class="md-form" id="">
                  <p>
                    <img class="nav-icon" src="img/svg/check-1.svg"> We have receieved your latest payment - many thanks</p>
                  <p class="indent">Your account is subscribed to our <strong>Standard</strong> package, paying <strong>Monthly</strong></p>
                  <p class="indent">Your next payment for <strong>£0.00</strong> (including VAT) is due on <strong>1st August 3247</strong>, which we'll take automatically via your chosen payment method</p>
                  <button data-toggle="modal" data-target="#" id="settings-manage-payments">Manage Payments</button>
                  <hr style="margin: 2rem !important">
                  <p class="indent">You are currently paying for <strong>0</strong> team members</p>
                  <button class="manage-team" data-toggle="modal">Manage teams</button>
                  <hr style="margin: 2rem !important">
                  <p class="indent">Your email address for billing is <strong>someone@example.com</strong><br>Enter a new email address below if you would like to change it</p>
                  <div class="md-form indent">
                    <input type="text" id="billing-email" class="form-control" placeholder="someone-else@example.com">
                  </div>
                </div>
                <hr>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>