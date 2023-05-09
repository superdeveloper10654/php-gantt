<script>
  
  $('.save-profile').click(function(event) {
    $.getJSON("beta.ajax.php?action=update_profile&first_name=" + $("#profile_first_name").val() + "&last_name=" + $("#profile_last_name").val() + "&telephone_number=" + $("#profile_telephone_number").val(), function(data) {
      location.reload();
    });
  });

  $(".add-team-batch").click(function(e) {
    $.getJSON("beta.ajax.php?action=add_team_batch&team=" + JSON.stringify(window.ibex_gantt_config.teamMembers), function(data) {
      window.location.href = "beta.php?id=" + $("#programme_id").val();
    });
  });

  $("#team_member_groups").select2({
    closeOnSelect: false,
    placeholder: "",
    multiple: true
  });

  $('.copy-share-link').click(function(e) {
    $(this).html('Copied');
    setTimeout(function() {
      $('.copy-share-link').html('Copy');
    }, 3000);
  });

  $('.copy-collaboration-link').click(function(e) {
    $(this).html('Link copied');
    setTimeout(function() {
      $('.copy-collaboration-link').html('Copy link');
    }, 5000);
  });

  //var clipboard = new Clipboard(".copy-share-link", 
  //{
  //text: function(trigger) 
  //{
  //return $(trigger).data('url').trim();
  //}
  //});

  //var clipboardCollaboration = new Clipboard(".copy-collaboration-link", 
  //{
  //text: function(trigger) 
  //{
  //return $(trigger).data('url').trim();
  //}
  //});

  $('.generate-share-link').click(function(e) {
    $.getJSON("beta.ajax.php?action=generate_share_link", function(data) {
      $("#administration_sharing_url").val(data.share_link);
      $(".copy-share-link").attr('data-url', data.share_link);
    });
  });

  window.listTeamMembers = function listTeamMembers() {
        $("#table_group_members_ui").empty();
        $.getJSON("beta.ajax.php?action=get_team_members", function(data) {
          $.each(data.team_members, function(index) {
            var userName = data.team_members[index].first_name + " " + data.team_members[index].last_name;
            if (data.team_members[index].first_name == null) {
              userName = data.team_members[index].email_address;
            }
            var lastOnline = " - not yet";
            if (data.team_members[index].heartbeat_time != null) {
              var dateOnline = moment.unix(data.team_members[index].heartbeat_time);
              lastOnline = dateOnline.fromNow();
            }
            // Get group names
            var groups = "";
            $.each(data.team_members[index].groups, function(indexGroups) {
              groups = groups + data.team_members[index].groups[indexGroups].name + ", ";
            });
            groups = groups.slice(0, -2)
            $("#table_group_members_ui").append("<div class='edit-team-member-groups' data-id='" + data.team_members[index].id + "'><div class='user-name'><span>" + userName + "</span><img class='edit-team-member' src='img/svg/edit.svg'></div><div class='user-group'>" + groups + "</div><div class='last-online'>" + lastOnline + "</div></div>");
          });
        });
      }
  
  window.listTeamMembersSetup = function listTeamMembersSetup() {
        $("#setup_table_group_members").empty();
        $.getJSON("beta.ajax.php?action=get_team_members", function(data) {
          $.each(data.team_members, function(index) {

            var userName = data.team_members[index].first_name + " " + data.team_members[index].last_name;
            if (data.team_members[index].first_name == null) {
              userName = data.team_members[index].email_address;
            }
            // Get group names
            var groups = "";
            $.each(data.team_members[index].groups, function(indexGroups) {
              groups = groups + data.team_members[index].groups[indexGroups].name + ", ";
            });
            groups = groups.slice(0, -2)
            $("#setup_table_group_members").append("<div class='edit-team-member-groups' data-id='" + data.team_members[index].id + "'><div class='user-name'><span>" + userName + "</span><img class='edit-team-member' src='img/svg/edit.svg'></div><div class='user-group'>" + groups + "</div></div>");
          });
        });
      }

  $(".save-team-member").click(function(e) {
    if ($("#team_member_new").val() == "true") {
      var groups = JSON.stringify($('#team_member_groups').select2("val"));
      $("#modal_view_user_groups").modal('hide');
      $.getJSON("beta.ajax.php?action=add_user&first_name=" + $("#team_member_first_name").val() + "&last_name=" + $("#team_member_last_name").val() + "&email_address=" + $("#team_member_email_address").val() + "&user_groups=" + groups, function(data) {
        $("#new_user_add_email_address").val('');
        $("#team_member_first_name").val('');
        $("#team_member_last_name").val('');
        $("#team_member_email_address").val('');
        listTeamMembers();
        $("#modal_manage_team").modal('show');
        $("#modal_view_user_groups").modal('hide');
      });
    } else {
      $.getJSON("beta.ajax.php?action=save_user&id=" + $("#team_member_id").val() + "&first_name=" + $("#team_member_first_name").val() + "&last_name=" + $("#team_member_last_name").val(), function(data) {
        $("#new_user_add_email_address").val('');
        $("#team_member_first_name").val('');
        $("#team_member_last_name").val('');
        $("#team_member_email_address").val('');
        listTeamMembers();
        $("#modal_manage_team").modal('show');
        $("#modal_view_user_groups").modal('hide');
      });
    }
  });

  $(".delete-team-member").click(function(e) {
    $.getJSON("beta.ajax.php?action=delete_user&id=" + $("#team_member_id").val(), function(data) {
      listTeamMembers();
      $("#modal_view_user_groups").modal('hide');
    });
  });
  
   $(".setup-save-team-member").click(function(e) {
        if ($("#setup_team_member_new").val() == "true") {
          var groups = JSON.stringify($('#setup_team_member_groups').select2("val"));
          // $("#modal_view_user_groups").modal('hide');
          // Add new
          $.getJSON("beta.ajax.php?action=add_user&first_name=" + $("#team_member_first_name").val() + "&last_name=" + $("#team_member_last_name").val() + "&email_address=" + $("#team_member_email_address").val() + "&user_groups=" + groups, function(data) {
            $("#setup_new_user_add_email_address").val('');
            $("#setup_team_member_first_name").val('');
            $("#setup_team_member_last_name").val('');
            $("#setup_team_member_email_address").val('');
            listTeamMembersSetup();
            // $("#modal_manage_team").modal('show');
            // $("#modal_view_user_groups").modal('hide');
          });
        } else {
          $.getJSON("beta.ajax.php?action=save_user&id=" + $("#team_member_id").val() + "&first_name=" + $("#team_member_first_name").val() + "&last_name=" + $("#team_member_last_name").val(), function(data) {
            $("#setup_new_user_add_email_address").val('');
            $("#setup_team_member_first_name").val('');
            $("#setup_team_member_last_name").val('');
            $("#setup_team_member_email_address").val('');
            listTeamMembersSetup();
            //$("#modal_manage_team").modal('show');
            // $("#modal_view_user_groups").modal('hide');
          });
        }
      });
  
  $(".setup-delete-team-member").click(function(e) {
        $.getJSON("beta.ajax.php?action=delete_user&id=" + $("#team_member_id").val(), function(data) {
          listTeamMembersSetup();
          // $("#modal_view_user_groups").modal('hide');
        });
      });


$(".manage-team").click(function(e) {
        $('#modal_messages').modal("hide");
        listTeamMembers();
        $('.user-group-links').select2({
          minimumResultsForSearch: -1,
          placeholder: function() {
            $(this).data('placeholder');
          }
        });
        //$('#settings').removeClass("show active");
        $('#modal_manage_team').modal("show");

        //$('[data-toggle="collapse"]').show();
      });
  
  $(".manage-teams").click(function(e) {
        /*
        $('#modal_messages').modal("hide");
        listTeamMembers();
        */
        $('.user-group-links').select2({
          minimumResultsForSearch: -1,
          placeholder: function() {
            $(this).data('placeholder');
          }
        });
        $('#modal_manage_team').modal("show");
        $('#accordionTeamsEditor-Teams').show();
        $('#accordionTeamsEditor-Permissions').remove();
        $('#accordionTeamsEditor-People').hide();
      });

  $(".manage-people").click(function(e) {
        $('#modal_messages').modal("hide");
        listTeamMembers();
        /*
        $('.user-group-links').select2({
          minimumResultsForSearch: -1,
          placeholder: function() {
            $(this).data('placeholder');
          }
        });
        */
        $('#modal_manage_team').modal("show");
        $('#accordionTeamsEditor-Teams').hide();
        $('#accordionTeamsEditor-Permissions').remove();
        $('#accordionTeamsEditor-People').show();
      });
  
  

  
$('#team_member_groups').on("select2:select", function(e) {
        $.getJSON("beta.ajax.php?action=add_user_to_group&user_id=" + $("#team_member_id").val() + "&group_id=" + e.params.data.id, function(data) {});
      });

      $('#team_member_groups').on("select2:unselect", function(e) {
        $.getJSON("beta.ajax.php?action=remove_user_from_group&user_id=" + $("#team_member_id").val() + "&group_id=" + e.params.data.id, function(data) {});
      });

      $('#messages_team_member_groups').on("select2:select", function(e) {
        $.getJSON("beta.ajax.php?action=add_user_to_group&user_id=" + $("#team_member_id").val() + "&group_id=" + e.params.data.id, function(data) {});
      });

      $('#messages_team_member_groups').on("select2:unselect", function(e) {
        $.getJSON("beta.ajax.php?action=remove_user_from_group&user_id=" + $("#team_member_id").val() + "&group_id=" + e.params.data.id, function(data) {});
      });
  

  $(document).on("click", ".edit-team-member-groups", function() {

        $.getJSON("beta.ajax.php?action=get_team_member&id=" + $(this).data('id'), function(data) {
          $("#team_member_new").val('false');
          $(".delete-team-member").show();
          $("#team_member_id").val(data.team_member.id);
          $("#team_member_name").text(data.team_member.first_name + " " + data.team_member.last_name);
          $("#team_member_first_name").val(data.team_member.first_name);
          $("#team_member_last_name").val(data.team_member.last_name);
          $("#team_member_email_address").val(data.team_member.email_address);

          // Read user gorups into array
          var userGroups = [];

          $.each(data.team_member.groups, function(index) {
            userGroups.push(data.team_member.groups[index].id);

          });



          $("#team_member_groups").empty();
          $.each(data.available_groups, function(index) {
            var selected = "";
            if (userGroups.includes(data.available_groups[index].id) == true) {
              var o = new Option(data.available_groups[index].name, data.available_groups[index].id);
              o.selected = true;
              $("#team_member_groups").append(o);
            } else {
              var o = new Option(data.available_groups[index].name, data.available_groups[index].id);
              $("#team_member_groups").append(o);
            }
          });

          if (data.team_member.id == data.self_id) {
            $(".delete-team-member").hide();
          }

          $("#modal_view_user_groups").modal('show');

        });

      });
  
  function testEmailAddressValid(emailAddress) {
    var pattern = new RegExp("^[_A-Za-z0-9-]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$");
    return pattern.test(emailAddress);
  }

  $('#team_member_invite_email_address').keypress(function(event) {
        if (event.keyCode == 13 && $('#team_member_invite_email_address').val().trim() != "" && testEmailAddressValid($('#team_member_invite_email_address').val().trim()) == true) {
          var html = '<div class="chip">' + $('#team_member_invite_email_address').val() + '<img class="action-icons remove-team-member data-email" src="img/svg/bin-1.svg">"' + $('#team_member_invite_email_address').val() + '"></i></div>';
          $('.team-members-invite-container').append(html);
          window.ibex_gantt_config.teamMembers.push($('#team_member_invite_email_address').val());
          $('#team_member_invite_email_address').val('');
        }
      });


      $('.invite-team-member').click(function(event) {
        if ($('#team_member_invite_email_address').val().trim() != "" && testEmailAddressValid($('#team_member_invite_email_address').val().trim()) == true) {
          var html = '<div class="chip chip-team" style="float: left !important">' + $('#team_member_invite_email_address').val() + '<img src="img/svg/bin-1.svg" style="margin-left: 8px" class="remove-team-member" data-email="' + $('#team_member_invite_email_address').val() + '"></img></div>';
          $('.team-members-invite-container').append(html);
          window.ibex_gantt_config.teamMembers.push($('#team_member_invite_email_address').val());
          $('#team_member_invite_email_address').val('');
          $('#team_member_invite_email_address').focus();
        }
      });

   $(document).on("click", ".remove-team-member", function() {
        $(this).parent().hide();
        var index = $.inArray($(this).data("email"), window.ibex_gantt_config.teamMembers);
        if (index != -1) {
          window.ibex_gantt_config.teamMembers.splice(index, 1);
        }
      });


  $(".add-team-member").click(function(e) {
        if ($("#new_team_member_email_address").val().trim() != '') {
          $.getJSON("beta.ajax.php?action=add_team_member&email_address=" + $("#new_team_member_email_address").val() + "&group_id=" + window.ibex_gantt_config.activeUserGroupID, function(data) {
            $("#new_team_member_email_address").val('');
            $("#table_group_members").html('');
            $.each(data.members, function(index) {
              if (data.members[index].within_group == "true") {
                $("#table_group_members").append("<div><div class='toggle-team-member-inclusion' data-group='" + window.ibex_gantt_config.activeUserGroupID + "' data-index='" + data.members[index].id + "' style='width: 100%; border-top: 0px !important'><img src='img/svg/bin-1.svg'  style='height: 16px'> <span style='color: #008B00'>" + data.members[index].name + "</span></div></div>");
              } else {
                $("#table_group_members").append("<div><div class='toggle-team-member-inclusion' data-group='" + window.ibex_gantt_config.activeUserGroupID + "' data-index='" + data.members[index].id + "' style='width: 100%; border-top: 0px !important'><img src='img/svg/bin-1.svg'  style='height: 16px'> <span style='color: #d9d9d9'>" + data.members[index].name + "</span></div></div>");
              }
            });
          });
        }
      });
  
  $(".messages-add-team-member").click(function(e) {
        if ($("#messages_new_team_member_email_address").val().trim() != '') {
          $.getJSON("beta.ajax.php?action=add_team_member&email_address=" + $("#messages_new_team_member_email_address").val() + "&group_id=" + window.ibex_gantt_config.activeUserGroupID, function(data) {
            $("#messages_new_team_member_email_address").val('');
            $("#messages_table_group_members").html('');
            $.each(data.members, function(index) {
              if (data.members[index].within_group == "true") {
                $("#messages_table_group_members").append("<div><div class='toggle-team-member-inclusion' data-group='" + window.ibex_gantt_config.activeUserGroupID + "' data-index='" + data.members[index].id + "' style='width: 100%; border-top: 0px !important'><img src='img/svg/bin-1.svg'  style='height: 16px'> <span style='color: #008B00'>" + data.members[index].name + "</span></div></div>");
              } else {
                $("#messages_table_group_members").append("<div><div class='toggle-team-member-inclusion' data-group='" + window.ibex_gantt_config.activeUserGroupID + "' data-index='" + data.members[index].id + "' style='width: 100%; border-top: 0px !important'><img src='img/svg/bin-1.svg'  style='height: 16px'> <span style='color: #d9d9d9'>" + data.members[index].name + "</span></div></div>");
              }
            });
          });
        }
      });
  
  $(".setup_add-team-member").click(function(e) {
        if ($("#setup_new_team_member_email_address").val().trim() != '') {
          $.getJSON("beta.ajax.php?action=add_team_member&email_address=" + $("#new_team_member_email_address").val() + "&group_id=" + window.ibex_gantt_config.activeUserGroupID, function(data) {
            $("#setup_new_team_member_email_address").val('');
            $("#setup_table_group_members").html('');
            $.each(data.members, function(index) {
              if (data.members[index].within_group == "true") {
                $("#setup_table_group_members").append("<div><div class='toggle-team-member-inclusion' data-group='" + window.ibex_gantt_config.activeUserGroupID + "' data-index='" + data.members[index].id + "' style='width: 100%; border-top: 0px !important'><img src='img/svg/bin-1.svg'  style='height: 16px'> <span style='color: #008B00'>" + data.members[index].name + "</span></div></div>");
              } else {
                $("#setup_table_group_members").append("<div><div class='toggle-team-member-inclusion' data-group='" + window.ibex_gantt_config.activeUserGroupID + "' data-index='" + data.members[index].id + "' style='width: 100%; border-top: 0px !important'><img src='img/svg/bin-1.svg'  style='height: 16px'> <span style='color: #d9d9d9'>" + data.members[index].name + "</span></div></div>");
              }
            });
          });
        }
      });

  $(document).on("click", ".add-new-user", function() {
        if ($("#new_user_add_email_address").val() != "") {
          $("#team_member_groups").empty();
          $.getJSON("beta.ajax.php?action=get_user_groups", function(data) {
            $.each(data.groups, function(index) {
              var o = new Option(data.groups[index].name, data.groups[index].id);
              $("#team_member_groups").append(o);
            });

            $(".delete-team-member").hide();
            var emailAddress = $("#new_user_add_email_address").val();
            $("#team_member_name").text('Add new team member');
            $("#team_member_new").val('true');
            $("#team_member_email_address").val(emailAddress);
            $("#modal_view_user_groups").modal('show');
          });
        }
      });

      $(document).on("click", ".setup-add-new-user", function() {
        if ($("#setup_new_user_add_email_address").val() != "") {
          $("#team_member_groups").empty();
          $.getJSON("beta.ajax.php?action=get_user_groups", function(data) {
            $.each(data.groups, function(index) {
              var o = new Option(data.groups[index].name, data.groups[index].id);
              $("#team_member_groups").append(o);
            });

            $(".setup-delete-team-member").hide();
            var emailAddress = $("#setup_new_user_add_email_address").val();
            $("#team_member_name").text('Add new team member');
            $("#team_member_new").val('true');
            $("#team_member_email_address").val(emailAddress);
            $("#modal_view_user_groups").modal('show');
          });
        }
      });
  
  $(document).on("click", ".messages-add-new-user", function() {
        if ($("#messages_new_user_add_email_address").val() != "") {
          $("#messages_team_member_groups").empty();
          $.getJSON("beta.ajax.php?action=get_user_groups", function(data) {
            $.each(data.groups, function(index) {
              var o = new Option(data.groups[index].name, data.groups[index].id);
              $("#messages_team_member_groups").append(o);
            });

            $(".setup-delete-team-member").hide();
            var emailAddress = $("#messages_new_user_add_email_address").val();
            $("#messages_team_member_name").text('Add new team member');
            $("#messages_team_member_new").val('true');
            $("#messages_team_member_email_address").val(emailAddress);
            //$("#modal_view_user_groups").modal('show');
          });
        }
    $(this).notify("Your invitation has been sent", "success", {
          position: "right"
      });
      });
  
  $(document).on('click', '.delete-group', function(e) {
        $(".team-members-container").hide();
        $.getJSON("beta.ajax.php?action=delete_group&id=" + $(this).data("index"), function(data) {
          $("#table_groups > tbody").html('');
          $("#table_group_members").html('');
          $.each(data.user_groups, function(index) {
            $("#table_groups > tbody").append("<tr><td>" + data.user_groups[index].name + "</td><td data-index='" + data.user_groups[index].id + "'></div> <div class='delete-group' data-index='" + data.user_groups[index].id + "'><img src='img/svg/bin-1.svg'  style='height: 16px'></div></td></tr>");
          });
        });
      });

      $(document).on('click', '.delete-group', function(e) {
        $(".setup_team-members-container").hide();
        $.getJSON("beta.ajax.php?action=delete_group&id=" + $(this).data("index"), function(data) {
          $("#setup_table_groups > tbody").html('');
          $("#setup_table_group_members").html('');
          $.each(data.user_groups, function(index) {
            $("#setup_table_groups > tbody").append("<tr><td>" + data.user_groups[index].name + "</td><td data-index='" + data.user_groups[index].id + "'></div> <div class='delete-group' data-index='" + data.user_groups[index].id + "'><img src='img/svg/bin-1.svg'  style='height: 16px'></div></td></tr>");
          });
        });
      });

      $(document).on('click', '.delete-group', function(e) {
        $(".messages_team-members-container").hide();
        $.getJSON("beta.ajax.php?action=delete_group&id=" + $(this).data("index"), function(data) {
          $("#messages_table_groups > tbody").html('');
          $("#messages_table_group_members").html('');
          $.each(data.user_groups, function(index) {
            $("#messages_table_groups > tbody").append("<tr><td>" + data.user_groups[index].name + "</td><td data-index='" + data.user_groups[index].id + "'></div> <div class='delete-group' data-index='" + data.user_groups[index].id + "'><img src='img/svg/bin-1.svg'  style='height: 16px'></div></td></tr>");
          });
        });
      });


      $(document).on('click', '.view-group-members', function(e) {
        $(".team-members-container").show();
        window.ibex_gantt_config.activeUserGroupID = $(this).data("index");
        $('#table_groups > tbody  > tr > td').each(function() {
          $(this).css('color', 'black')
        });
        $(this).closest("tr").find('td:first').css('color', 'red');
        var groupID = $(this).data("index");
        $.getJSON("beta.ajax.php?action=view_group_members&id=" + $(this).data("index"), function(data) {
          $("#table_group_members").html('');
          $.each(data.members, function(index) {
            if (data.members[index].within_group == "true") {
              $("#table_group_members").append("<div><div class='toggle-team-member-inclusion' data-group='" + groupID + "' data-index='" + data.members[index].id + "' style='width: 100%; border-top: 0px !important'><img src='img/svg/check-1.svg'> <span style='color: #008B00'>" + data.members[index].name + "</span></div></div>");
            } else {
              $("#table_group_members").append("<div><div class='toggle-team-member-inclusion' data-group='" + groupID + "' data-index='" + data.members[index].id + "' style='width: 100%; border-top: 0px !important'><img src='img/svg/bin-1.svg'  style='height: 16px'> <span style='color: #d9d9d9'>" + data.members[index].name + "</span></div></div>");
            }
          });
        });
      });



      $(document).on('click', '.toggle-team-member-inclusion', function(e) {
        var groupID = $(this).data('group');
        var userID = $(this).data('index');
        $.getJSON("beta.ajax.php?action=toggle_team_member_inclusion&id=" + $(this).data("index") + "&group_id=" + groupID, function(data) {
          $("#table_group_members").html('');
          $.each(data.members, function(index) {
            if (data.members[index].within_group == "true") {
              $("#table_group_members").append("<div><div class='toggle-team-member-inclusion'  data-group='" + groupID + "' data-index='" + data.members[index].id + "' style='width: 100%; border-top: 0px !important'><img src='img/svg/check-1.svg'> <span style='color: #008B00'>" + data.members[index].name + "</span></div></div>");
            } else {
              $("#table_group_members").append("<div><div class='toggle-team-member-inclusion'  data-group='" + groupID + "' data-index='" + data.members[index].id + "' style='width: 100%; border-top: 0px !important'><img src='img/svg/bin-1.svg'  style='height: 16px'> <span style='color: #d9d9d9'>" + data.members[index].name + "</span></div></div>");
            }
          });
        });
      });
  
  $(".messages-add-user-group").click(function(e) {
        if ($("#messages_new_user_group_name").val().trim() != '') {
          $.getJSON("beta.ajax.php?action=add_user_group&name=" + $("#messages_new_user_group_name").val(), function(data) {
            $("#messages_new_user_group_name").val('');
            $("#messages_table_groups > tbody").html('');
            $.each(data.user_groups, function(index) {
              $("#messages_table_groups > tbody").append("<tr><td>" + data.user_groups[index].name + "</td><td data-index='" + data.user_groups[index].id + "'></div> <div class='delete-group' data-index='" + data.user_groups[index].id + "'><img src='img/svg/bin-1.svg'  style='height: 16px'></div></td></tr>");
            });
          });
        }
    $(this).notify("Your new team was added", "success", {
          position: "right"
      });
      });
  
  
  // Invite?
      if ($("#invite_team").val() == "true") {

        $("#modal_invite_team").modal('show'); // modal_invite_team

      }
  
  $(".close-teams-modal").click(function(e) {
        $('#settings').addClass("show active");
        //$('[data-toggle="collapse"]').show();
      });
  
</script>