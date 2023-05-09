<div role="tabpanel" class="tab-pane animated fadeIn" id="files" aria-labelledby="files-tab">
  <div class="upload-percentage"></div>
  <div class="no-files" style="display: none">
    <div class="row">
                <div class="col-md-10" style="padding: 50px;">
                <h4 style="color: <?=$_SESSION['user']['opacity_font']?>" id="no-files">You have no files yet</h4>
              <p style="color: <?=$_SESSION['user']['opacity_font']?>" id="upload-files">Upload your files to share or attach to your tasks</p>
                <button class="col-md-4 mx-auto huge add-file add-file-unique pulse">
          <img class="setup " src="img/svg/file-upload-cloud.svg">
          <h4>Upload File</h4>
          <p>(no size limit)</p>
        </button>
        <input type="file" style="display: none" id="file_handler_unique" onchange='uploadUniqueFile()' accept=".png, .jpg, .jpeg">
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
         <h4 style="color: <?=$_SESSION['user']['opacity_font']?>">Shared Files</h4>
    <p style="color: <?=$_SESSION['user']['opacity_font']?>" id="shared-files">Upload your files and attach them to tasks (optional)</p>
        <div class="" id="table_files">
        </div>
      </div>
      <div class="col-md-2" id="files-sidebar">
          <div class="mx-auto">
            <button class="mx-auto huge add-file add-file-unique pulse">
              <img class="setup " src="img/svg/file-upload-cloud.svg">
              <h4>Upload<br>File</h4>
            </button>
            <input type="file" style="display: none" id="file_handler_unique" onchange='uploadUniqueFile()' accept=".png, .jpg, .jpeg">
          </div>

        <p style="padding: 20px; color: #ddd; text-align: left;">
          You can upload any size file, although the time it takes will depend on your internet speed
        </p>
      </div>
  </div>
</div>