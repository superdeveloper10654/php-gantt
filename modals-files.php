  <div class="modal animated fadeIn" id="modal_edit_file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Files Editor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
        <div class="modal-body">
          <input type="hidden" id="file_id" value="0" accept=".png, .jpg, .jpeg">
          <div class="col">
            <p class="small file-intro"></p>
            <hr>

            <div class="col">
              <p class="small" style="margin:0">Attached to:</p>
              <div class="md-form" style="margin: 0 0 3rem 0;">
                <select id="file_task_links" name="file_tasks[]" class="form-control" multiple="multiple" style="width: 100%" placeholder="">
									 </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="mr-auto delete-file">Delete</button>
          <button data-dismiss="modal" aria-label="Close">Save</button>
        </div>
      </div>
    </div>
  </div>
  