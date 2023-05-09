<script>
  
  $(document).on('click', '.dismiss-broadcast', function(e) {
    $(this).parent().parent().hide();
    if ($(".broadcast-message-ui:visible").length == 0) {
      $("#prompt").hide();
    }
    $.getJSON("beta.ajax.php?action=dismiss_broadcasts", function(data) {});
  });

  $(document).on('click', '.manage-broadcasts', function(e) {
    $.getJSON("beta.ajax.php?action=get_activity_broadcasts", function(data) {
      $("#table_broadcasts >tbody").empty();
      $.each(data.broadcasts, function(index) {
        var time = moment.unix(data.broadcasts[index].created).format("DD/MM/YYYY HH:mm");
        var html = "<tr><td style=''>" + data.broadcasts[index].message + "</td><td class='small'>Added by " + data.broadcasts[index].first_name + " " + data.broadcasts[index].last_name + " at " + time + "</td></tr>";
        $("#table_broadcasts >tbody").append(html);
      });
      $("#modal_broadcasts").modal('show');
    });
  });

  $(document).on('click', '.send-broadcast-message', function(e) {
    if ($("#broadcast_text").val() != "") {
      $.getJSON("beta.ajax.php?action=send_broadcast_message&message=" + $("#broadcast_text").val(), function(data) {
        $("#table_broadcasts >tbody").empty();
        $.each(data.broadcasts, function(index) {
          var time = moment.unix(data.broadcasts[index].created).format("DD/MM/YYYY HH:mm");
          var html = "<tr><td style=''>" + data.broadcasts[index].message + "</td><td class='small'>Added by " + data.broadcasts[index].first_name + " " + data.broadcasts[index].last_name + " at " + time + "</td></tr>";
          $("#table_broadcasts >tbody").append(html);
        });
        $("#modal_broadcasts").modal('show');
        $("#broadcast_text").val('');
      });
    } else {
      alert('No message entered. Doh!')
    }
  });
  
</script>