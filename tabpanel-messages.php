 <div role="tabpanel" class="tab-pane animated fadeIn" id="site-messages" aria-labelledby="site-messages-tab" style="width: 300px; display: none;">
                         <div class="modal animated fadeIn" id="modal_messages" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog" role="document" id="direct-messages">
              <div class="modal-content">
                <div class="modal-header" style="box-shadow: 0 3px 10px 1px rgb(0 0 0 / 10%);">
                  <h4 class="modal-title">Messages</h4>
                  <button type="button" class="close" id="messages-shrink"><span aria-hidden="true"><img src="img/svg/shrink.svg" title="View as sidebar"></span></button>
                          <button type="button" class="close" id="messages-expand" style="display: none"><span aria-hidden="true"><img src="img/svg/expand.svg" title="View as full-screen"></span></button>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
                </div>
                <div class="modal-body" style="overflow: hidden; height: calc(100vh - 60px - 74px - 90px);">
                  <div class="card">
                      <div class="row">
                        <div class="col-md-2" id="messages-contacts">
                          <div class="accordion md-accordion" id="accordionmessagesContacts" role="tablist" aria-multiselectable="true">
                          </div>
                          <div class="card" style="margin-bottom: 20px; border-radius: 10px;">
                            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionmessagesContacts" href="#collapsemessagesContacts" aria-expanded="false" aria-controls="collapsemessagesContacts" style="padding-left: 10px; border: none; ">
                              <p class="mb-0">Your contacts</p>
                            </a>
                            <div id="collapsemessagesContacts" class="" role="tabpanel" aria-labelledby="headingmessagesContacts" data-parent="#accordionmessagesContacts">
                              <div class="card-body" style="padding: 0;">
                                <div class="row">
                                  <div class="col">

                                    <table id="table_message_contacts" class="table table-sm">
                                      <tbody>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!--
                          <div class="card" id="messages-invite">
                            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionMessagesInvite" href="#collapseMessagesInvite" aria-expanded="false" aria-controls="collapseMessagesInvite" style="padding-left: 10px; border: none; background: #fff;">
                              <p class="mb-0">Invite People</p>
                            </a>
                            <div id="collapseMessagesInvite" class="" role="tabpanel" aria-labelledby="headingMessagesInvite" data-parent="#accordionMessagesInvite">
                              <div class="card-body" style="padding-top: 0; background: #fff;">
                                <div id="messages-invite-wrapper">
                                  <div class="row">
                                    <div class="md-form" style="margin: 0;">
                                      <p class="text-muted" style="margin: 5px; text-align: left;">Enter an email address to send an invite</p>
                                      <input type="text" id="messages_new_user_add_email_address" class="form-control" placeholder="e.g. eric@example.com">
                                      <span class="input-group-append input-group-text messages-add-new-user">Send</span>

                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card" id="messages-teams">
                            <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionMessagesTeams" href="#collapseMessagesTeams" aria-expanded="false" aria-controls="collapseMessagesTeams" style="padding-left: 10px; border: none; background: #fff;">
                              <p class="mb-0">Manage Teams</p>
                            </a>
                            <div id="collapseManageTeams" class="" role="tabpanel" aria-labelledby="headingMessagesTeams" data-parent="#accordionMessagesTeams">
                              <div class="card-body" style="padding-top: 0; background: #fff;">
                                <div id="messages-teams-wrapper">
                                  <div class="row">
                                    <div class="md-form" style="margin: 0;">
                                      <p class="text-muted" style="margin: 5px; text-align: left;">Enter a team name, one at a time</p>
                                      <input type="text" id="messages_new_user_group_name" class="form-control" placeholder="e.g. Electricians">
                                      <span class="input-group-append input-group-text messages-add-user-group">Add</span>

                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="" style="overflow: auto; margin-top: 20px;">
                                      <p class="text-muted" style="margin: 5px; text-align: left;">These are your teams:</p>
                                      <table id="messages_table_groups" class="table table-sm"></table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        
--></div>

                        <div class='col-md-10' id="messages-content">
                          <h4 id="conversation_you_and_name"></h4>
                          <div id="select-recipient-prompt">
                            <img src="img/svg/message.svg">
                            <h2>Select a team member<br> to start a discussion</h2>
                          </div>
                          <div class="message-container" style="display: none">
                            <table id="table_thread" class="table table-sm">
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>
                <div class="modal-footer col-md-12" id="messages-footer" style="position: absolute; bottom: 0; padding: 0;">
                  
                  <div class="md-form input-group col-md-10" style="display: none" id="new-message-input-group">
                    
                    <input data-index='0' type="text" id="new_message_text" class="form-control" placeholder="What do you want to say?" data-sender="<?=$_SESSION['user']['id']?>" data-recipient='0'>
                      <div class="input-group-append">
                      <span class="input-group-text send-message">Send</span>
                    </div>
                  </div>
                 
                </div>
              </div>
            </div>
          </div>
                      

<div class="modal-header" style="">
                          <h4 class="modal-title">Messages</h4>
                          <button type="button" class="close" id="messages-close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
                       </div>
      <div class="no-messages" style="padding: 20px 0 0 20px ;display: none">
                            <p>You've read all your messages</p>
                        </div>
      <div style="padding: 0 20px;">
      <table id="table_message_contacts" class="table table-sm">
                  <tbody>
                  </tbody>
                </table>
      </div>
 

</div>