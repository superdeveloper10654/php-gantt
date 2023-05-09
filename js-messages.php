<script>
  
  setInterval(function() {
        ganttHeartbeat();
      }, 10000);
		
		
      window.ganttHeartbeat = function ganttHeartbeat() {
			
			
			
        window.ibex_gantt_config.snapshotData = JSON.stringify(gantt.serialize());
		  
		  
       // updateTaskIndices();
		  var order_array = [];
        var tasks = gantt.getTaskByTime();
        for (var i = 0; i < tasks.length; i++) {
          var task = gantt.getTask(tasks[i].id);
          var id = task.id;
          var index = getIndexModified(task.id);
          task.order_ui = index;
          var obj = {
            'id': id,
            'index': index
          };
          order_array.push(obj);
        }
        var orderData = JSON.stringify(order_array);
        
		  
		  
		  
        $.getJSON("beta.ajax.php?action=heartbeat&ui_order=" + orderData, function(data) {
			  
			  $("#table_message_contacts >tbody").empty();
          $.each(data.contacts, function(index) {
            var lastOnline = "Offline";
            if (data.contacts[index].heartbeat_time != null) {
              var dateOnline = moment.unix(data.contacts[index].heartbeat_time);
              lastOnline = dateOnline.fromNow();
            }
            if (data.contacts[index].unread_messages > 0) {
              var html = "<tr class='select-recipient-thread' data-index='" + data.contacts[index].contact_id + "'><td style='width: 100%; line-height: inherit'><img src='" + data.contacts[index].avatar_url + "' style='height: 30px; margin: 0 10px 0 0;'>" + data.contacts[index].contact_name + " <span class='unread-figure'>" + data.contacts[index].unread_messages + " new</span></td></tr>";
              $("#unread-messages-count").html(data.contacts[index].unread_messages);
              $("button#show-messages").addClass("unread");
            } else {
              var html = "<tr class='select-recipient-thread' data-index='" + data.contacts[index].contact_id + "'><td style='width: 100%; line-height: inherit'><img src='" + data.contacts[index].avatar_url + "' style='height: 30px; margin: 0 10px 0 0; '>" + data.contacts[index].contact_name + "<div class='last-online'>" + lastOnline + "</div></td></tr>";
            }
            $("#table_message_contacts >tbody").append(html);
            
			});
			
			
			
          if (data.unread_messages == true) 
			 {
            $.getJSON("beta.ajax.php?action=get_ibex_support_messages", function(data) {
              $("#table_ibex_support_messages >tbody").empty();
              $.each(data.ibex_support_contact, function(index) {
                if (data.ibex_support_contact[index].unread_messages > 0) {
                  var html = "<tr class='select-recipient-thread' data-index='" + data.ibex_support_contact[index].contact_id + "'><td style='width: 100%; line-height: inherit'><img src='../img/logo.png' style='height: 30px; margin: 0 10px 0 0;'>" + data.ibex_support_contact[index].contact_name + " <span class='unread-figure'>" + data.ibex_support_contact[index].unread_messages + " new</span></td></tr>";
                } else {
                  var html = "<tr class='select-recipient-thread' data-index='" + data.ibex_support_contact[index].contact_id + "'><td style='width: 100%; line-height: inherit'><img src='../img/logo.png' style='height: 30px; margin: 0 10px 0 0; '>" + data.ibex_support_contact[index].contact_name + "</td></tr>";
                }
                $("#table_ibex_support_messages >tbody").append(html);
              });
            });
          } else {
            $(".no-messages").show();
          }
          if (data.heartbeat == false) {
            $(".container").hide();
            $("#modal_session_expired").modal('show');
          } else {
            if (data.last_save_time > window.ibex_gantt_config.lastSaveTime && data.last_save_author_id_diff == true)
				{
              $('.version-author').html('Your programme has been updated by ' + data.last_save_author);
              $("#modal_new_version").modal('show');
            }
          }
			 
        });
      }
		
		
		
		
		
		
		
      $(document).on('click', '.send-message', function(e) {
        if ($("#new_message_text").val() != "") {
          $.getJSON("beta.ajax.php?action=send_message&recipient_id=" + window.ibex_gantt_config.activeConversationRecipient + "&message=" + $("#new_message_text").val(), function(data) {
            //table_thread
            $("#table_thread >tbody").empty();
            $("#new_message_text").val('');
			
            $("#new_message_wysiwyg").empty();
            getConversationUpdates(window.ibex_gantt_config.activeConversationRecipient);
          });
        }
      });
      window.getConversationUpdates = function getConversationUpdates(recipientID) {
        if (recipientID == "0") {
          $("#new-message-input-group").hide();
          $("#conversation_you_and_name").html("Ibex Support");
        } else {
          $("#new-message-input-group").show();
        }
        $.getJSON("beta.ajax.php?action=get_thread&recipient_id=" + recipientID, function(data) {
          var contactName = data.contact_name;
          $("#table_thread >tbody").empty();
          $.each(data.thread, function(index) {
            var readStatus = "<span style='padding-left: 20px'>read</span>";
            if (data.thread[index].unread == "1") {
              readStatus = "<span style='padding-left: 20px'>pending</span>";
            }
            if (data.thread[index].sender_name == "You") {
              var auxText = "<strong>" + data.thread[index].sender_name + "</strong> &nbsp;" + moment.unix(data.thread[index].created).fromNow() + readStatus;
            } else {
              var auxText = "<strong>" + data.thread[index].sender_name + "</strong> &nbsp;" + moment.unix(data.thread[index].created).fromNow();
            }
            if (data.thread[index].sender_name == "You") {
              var html = "<tr><td><img src='<?=$_SESSION['user']['avatar_url']?>' style='height: 30px; float:left;'><div class='' style='padding-left: 35px; line-height: 30px;'>" + auxText + "</div><div class='message-text' style='padding-left: 35px;'>" + data.thread[index].text + "</div></td></tr>";
            } else {
              var html = "<tr><td><img src='" + data.counterparty_image_url + "' style='height: 30px; float:left;'><div class='' style='padding-left: 35px; line-height: 30px;'>" + auxText + "</div><div class='message-text' style='padding-left: 35px;'>" + data.thread[index].text + "</div></td></tr>";
            }
            $("#table_thread >tbody").append(html);
          });
        });
      }
      $('#new_message_text').bind('keypress', function(e) {
        if (e.which == 13) {
          if ($("#new_message_text").val() != "") {
            $.getJSON("beta.ajax.php?action=send_message&recipient_id=" + window.ibex_gantt_config.activeConversationRecipient + "&message=" + $("#new_message_text").val(), function(data) {
              $("#table_thread >tbody").empty();
              $("#new_message_text").val('');
              $("#new_message_wysiwyg").val('');
              getConversationUpdates(window.ibex_gantt_config.activeConversationRecipient);
            });
          }
        }
      });
      window.messageTimerRefresh;
      $('#modal_messages').on('hidden.bs.modal', function() {
        window.ibex_gantt_config.activeConversationRecipient = 0;
        clearInterval(messageTimerRefresh);
        $("#new_message_text").focus();
      })
      $(document).on('click', '.select-recipient-thread', function(e) {
        $("#modal_messages").modal('show');
        $('div#select-recipient-prompt').remove();
        $('div#new-message-input-group').show();
        var idSeek = $(this).data("index");
        window.ibex_gantt_config.activeConversationRecipient = $(this).data("index");
        $(".send-message").attr('data-index', $(this).data("index"));
        $("#new_message_text").attr('data-index', $(this).data("index"));
        try {
          clearInterval(messageTimerRefresh);
        } catch (er1) {}
        messageTimerRefresh = setInterval(function() {
          getConversationUpdates(idSeek);
        }, 5000);
        var el = $(this);
        $("#new_message_text").show();
        $(".send-message").show();
        $(".message-container").show();
        if ($(this).data("index") == "0") {
          $("#new-message-input-group").hide();
        } else {
          $("#new-message-input-group").show();
        }
        $.getJSON("beta.ajax.php?action=get_thread&recipient_id=" + $(this).data("index"), function(data) {
          var contactName = data.contact_name;
          $("#conversation_header").html("<img src='../img/logo.png' style='height: 30px; margin: 0 10px 0 0;0;'>" + data.contact_name);
          $("#conversation_you_and_name").html("You & " + data.contact_name);
          $("#table_thread >tbody").empty();
          getConversationUpdates(window.ibex_gantt_config.activeConversationRecipient);
        });
        $.getJSON("beta.ajax.php?action=get_message_states", function(data) {
          $("#table_message_contacts >tbody").empty();
          $.each(data.contacts, function(index) {
            if (data.contacts[index].unread_messages > 0) {
              var html = "<tr class='select-recipient-thread' data-index='" + data.contacts[index].contact_id + "'><td style='width: 100%; line-height: inherit'><img src='" + data.contacts[index].avatar_url + "' style='height: 30px; margin: 0 10px 0 0; '>" + data.contacts[index].contact_name + " <span class='unread-figure'>" + data.contacts[index].unread_messages + " new<span></td></tr>";
            } else {
              var html = "<tr class='select-recipient-thread' data-index='" + data.contacts[index].contact_id + "'><td style='width: 100%; line-height: inherit'><img src='" + data.contacts[index].avatar_url + "'  style='height: 30px; margin: 0 10px 0 0; '>" + data.contacts[index].contact_name + "</td></tr>";
            }
            $("#table_message_contacts >tbody").append(html);
          });
        });
      });
  
</script>