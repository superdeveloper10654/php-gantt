<script>
  if ($("#get_support").val() == "true") {
    $('#gantt').removeClass("show active");
    $('#ibex-support').show();
    $('#support-home-wrapper').show();
  };
  $('.load_support').click(function(e) {
    $('#st-trigger-effects').hide();
    window.location.href = "beta.php?id=" + $("#programme_id").val() + "&support=true";
    ('#gantt').removeClass("show active");
    ('#resources').removeClass("show active");
    ('#files').removeClass("show active");
    ('#messages').removeClass("show active");
    ('#activity').removeClass("show active");
    ('#settings').removeClass("show active");
    $('#ibex-support').show();
    $('#support-home-wrapper').show();
  });
  $('#close-support-home').click(function(e) {
    window.location.href = "beta.php?id=" + $("#programme_id").val();
  });
  $('.close-support-article').click(function(e) {
    $('#support-articles-wrapper').hide();
    $('.support-article').hide();
    $('#support-home-wrapper').show();
  });
  $('.open-articles-scheduling').click(function(e) {
    $('#support-home-wrapper').hide();
    $('.support-article').hide();
    $('#support-articles-wrapper').show();
  });
  $('#open-article-1').click(function(e) {
    $('#article-1').show();
  });
</script>