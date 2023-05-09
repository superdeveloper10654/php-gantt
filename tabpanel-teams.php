<div role="tabpanel" class="tab-pane animated fadeIn" id="teams" aria-labelledby="teams-tab">
              <div class="row">
                <div class="col-md-10" style="padding: 50px;">
                  <h4 style="color: <?=$_SESSION['user']['opacity_font']?>">Team Members</h4>
              <p style="color: <?=$_SESSION['user']['opacity_font']?>">Organise your people into teams (i.e. by department, skillset or trade)</p>
                <button class="col huge ml-auto manage-teams">
                  <img class="settings" src="img/svg/manage-teams.svg">
                  <h4 style="font-weight: bold;">Manage Teams</h4>
                  <p>Add or edit your teams</p>
                </button>
                <button class="col huge mr-auto manage-people">
                  <img class="settings" src="img/svg/manage-people.svg">
                  <h4 style="font-weight: bold;">Manage People</h4>
                  <p>Invite people to collaborate</p>
                  </button>
          <hr style="border: none;">
                <h4 style="color: <?=$_SESSION['user']['opacity_font']?>">Team Permissions</h4>
                <p style="color: <?=$_SESSION['user']['opacity_font']?>">Manage your teams' access permissions your account's features</p>
          <div class="row">
                <div class="groups-container"></div>
            <div class="container-permission-groups-settings"></div>
          </div>
        </div>
                <div class="col-md-2" id="teams-sidebar">
        <p style="padding: 20px; color: #ddd; text-align: left">
          Invite as many team members as you need. <br><br>You have <?=$team_members_count?> <?=$team_members_count_operator?> in your Ibex account.
        </p>
                  
      </div>
    </div>
</div>