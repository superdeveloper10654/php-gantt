  <div class="modal animated fadeIn" id="modal_loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
                       <h4 class="modal-title">Loading</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
        <div class="modal-body">
          <p>Preparing your programme...</p>
          <div class="preloader-wrapper big active">
            <div class="spinner-layer spinner-blue-only">
              <div class="circle-clipper left">
                <div class="circle"></div>
              </div>
              <div class="gap-patch">
                <div class="circle"></div>
              </div>
              <div class="circle-clipper right">
                <div class="circle"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

 <div class="modal animated fadeIn" id="modal_session_expired" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-body" style="height: auto !important;">
          <div class="card">
            <div class="card-body">
              <div class="col" style="text-align: center;">
          <img class="setup settings-check" src="img/svg/secure.svg"> 
                <h4>We've logged you out</h4>
              <p>You've been inactive for a while, so we've logged you out of Ibex to keep your data protected</6>
              <p>Please login again to continue</hp><br><br>
                <a href="https://beta.ibex.software/mmb-basic/"><button>Login</button></a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

  <div class="modal animated fadeIn" id="modal_new_version" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
                       <h4 class="modal-title">Please Reload</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
          <div class="card">
            <div class="card-body">
              <div class="col" style="text-align: center;">
          <img class="setup settings-check" src="img/svg/reload.svg">
          <h4 class="version-author"</h4>
          <p>Please reload to continue</p>
        </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="beta.php?id=<?=$_GET['id']?>"><button type="button" class="btn-green">Reload</button></a>
        </div>
      </div>
    </div>
  </div>
  <div class="modal animated fadeIn" id="modal_task_locked" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <ul class="nav nav-tabs">
          <li class="nav-item nav-item-main">
            <a class="nav-link-modal" role="tab" aria-expanded="true">
                       Locked</a>
          </li>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
        </ul>
      </div>
      <div class="modal-body">
          <div class="card">
            <div class="card-body">
              <p class="lock-author"></p>
              <p class="lock-author-release"></p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="" data-dismiss="modal">Wait</button>
        </div>
      </div>
    </div>
  </div>
