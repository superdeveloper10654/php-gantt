<?php         
// Do we have any broadcast messages to show?
$time_now = time();
$last_login = $_SESSION['last_login'];
$stmt = $db->prepare("SELECT t1.*,t2.first_name,t2.last_name FROM gantt_broadcasts t1 LEFT JOIN users t2 ON t2.id = t1.author_id WHERE t1.programme_id='$programme_id' AND t1.created >'$last_login' ORDER BY t1.created DESC");
$stmt->execute();
$broadcast_count = $stmt->rowCount();
$broadcasts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
  <?php
if ($broadcast_count > 0)
{
	?>

      <?php
	foreach ($broadcasts as $broadcast)
	{?>
        <div id="prompt" class="broadcast-message-ui" style="" data-index='<?=$broadcast[' id ']?>'>
          <img id="broadcast-view-image" src="img/svg/broadcast-view.svg">
            <h5>
              <?=$broadcast['message']?>
            </h5>
            <button type="button" class="dismiss-broadcast" data-index='<?=$broadcast[' id ']?>'><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>

        </div>
        <?php
	}?>
 
    <?php
}
?>

  <div class="modal animated fadeIn" id="modal_broadcasts" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-info" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Broadcasts Editor</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
                </div>
                <div class="modal-body">
                  <div class="card">
                    <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <div class="md-form input-group">
                          <p>Write a broadcast message to your teams, displayed when they log in</p>
                          <div class="md-form input-group" style="">
                            <input type="text" id="broadcast_text" class="form-control col-12" placeholder="What do you need to say?">
                            <div class="input-group-append">
                              <span class="input-group-text send-broadcast-message" style="font-size: 0.8em">Add</span>
                            </div>
                          </div>
                          <table id="table_broadcasts" class="table table-sm">
                            <thead>
                              <tr>
                                <th></th>
                                <th></th>
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
                </div>
                <div class="modal-footer">
                  <button class="" type="button" data-dismiss="modal">Send</button>
                </div>
              </div>
            </div>
          </div>
