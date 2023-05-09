<div role="tabpanel" class="tab-pane animated fadeIn" id="resources" aria-labelledby="resources-tab">
  <div class="no-resources" style="display: none">
    <div class="row">
                <div class="col-md-10" style="padding: 50px;">
                  <h4 style="color: <?=$_SESSION['user']['opacity_font']?>">You have no resources yet</h4>
              <p style="color: <?=$_SESSION['user']['opacity_font']?>">Add your resources within groups to be assigned to tasks</p>
                <button class="col huge manage-resource-groups pulse">
                  <img class="setup" src="img/svg/resource-group.svg">
              <h4 style="font-weight: bold;">Resource group</h4>
              <p>Create a resource group for your resources </p>
                </button>
                <button class="col huge mr-auto add-resource">
                  <img class="setup " src="img/svg/resource-item.svg">
              <h4 style="font-weight: bold;">Resource item</h4>
              <p>Add a resource item for you to allocate</p>
                  </button>
        </div>
                <div class="col-md-2" id="teams-sidebar">
        <p style="padding: 20px; color: #ddd; text-align: left">
          Invite as many team members as you need. <br><br>You have <?=$team_members_count?> <?=$team_members_count_operator?> in your Ibex account.
        </p>
                  
      </div>
    </div>

      </div>  
    <div class="row">
      <div class="col-md-10" style="padding: 50px;">
        <h4 style="color: <?=$_SESSION['user']['opacity_font']?>">Resource Items</h4>
        <p style="color: <?=$_SESSION['user']['opacity_font']?>">Add your resources within groups to be assigned to tasks</p>
        <table id="table_resources" style="width: inherit;">

        </table>
      </div>
      <div class="col-md-2" id="resources-sidebar">
        <div class="row">
          <button class="add-resource mx-auto huge pulse"><img class="resources-locked" src="img/svg/lock-closed.svg" style="display: none;"><img class="setup" src="img/svg/resource-item.svg"><h4>Add<br>Resource</h4></button>
        </div>
        <div class="row">
          <div class="card mx-auto">
            <div class="card-body">
              <input type="hidden" id="resource_edit_id" value="5240">
              <button id="resources-tab-add-group" class="manage-resource-groups" style="margin-bottom: 10px;">Add Group<img class="resources-locked" src="img/svg/lock-closed.svg" style="display: none;"></button><br>
             <button class="view-resource-groups">View Groups</button>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="card mx-auto">
            <div class="card-body">
              <button id="resources-tab-add-calendar" class="add-resource-calendar" style="margin-bottom: 10px;">Add Calendar<img class="locked" src="img/svg/lock-closed.svg" style="display: none;"></button><br>
              <button id="resources-tab-view-calendars" data-toggle="modal" data-target="#modal_edit_calendars">View Calendars</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>