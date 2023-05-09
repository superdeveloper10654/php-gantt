<script>
  
  $('#modal_date_range').on('show.bs.modal', function() {
        $('.mdb-select').material_select('destroy');
        $("#date_range_start_date_d").val(moment(window.ibex_gantt_config.rangeStartDate).format("DD"));
        $("#date_range_start_date_m").val(moment(window.ibex_gantt_config.rangeStartDate).format("MM"));
        $("#date_range_start_date_y").val(moment(window.ibex_gantt_config.rangeStartDate).format("YYYY"));
        $("#date_range_end_date_d").val(moment(window.ibex_gantt_config.rangeEndDate).format("DD"));
        $("#date_range_end_date_m").val(moment(window.ibex_gantt_config.rangeEndDate).format("MM"));
        $("#date_range_end_date_y").val(moment(window.ibex_gantt_config.rangeEndDate).format("YYYY"));
        $('.mdb-select').material_select();
      })
  
  $(".set-date-range").click(function(e) {
        $.getJSON("beta.ajax.php?action=set_internal_date_range&start=" + moment($("#date_range_start_date_d").val() + "/" + $("#date_range_start_date_m").val() + "/" + $("#date_range_start_date_y").val(), 'DD/MM/YYYY').format("YYYY-MM-DD") + "&end=" + moment($("#date_range_end_date_d").val() + "/" + $("#date_range_end_date_m").val() + "/" + $("#date_range_end_date_y").val(), 'DD/MM/YYYY').format("YYYY-MM-DD"), function(data) {
          gantt.config.start_date = moment($("#date_range_start_date_d").val() + "/" + $("#date_range_start_date_m").val() + "/" + $("#date_range_start_date_y").val(), 'DD/MM/YYYY').toDate();
          gantt.config.end_date = moment($("#date_range_end_date_d").val() + "/" + $("#date_range_end_date_m").val() + "/" + $("#date_range_end_date_y").val(), 'DD/MM/YYYY').toDate();
          window.ibex_gantt_config.rangeStartDate = gantt.config.start_date;
          window.ibex_gantt_config.rangeEndDate = gantt.config.end_date;
          gantt.render();
          $("#modal_date_range").modal('hide');
        });
      });

      $(".set-default-date-range").click(function(e) {
        $.getJSON("beta.ajax.php?action=set_internal_date_default", function(data) {
          gantt.config.start_date = moment().subtract(30, 'days').toDate();
          gantt.config.end_date = moment().add(30, 'days').toDate();
          window.ibex_gantt_config.rangeStartDate = gantt.config.start_date;
          window.ibex_gantt_config.rangeEndDate = gantt.config.end_date;
          gantt.render();
          var scrollNow = gantt.posFromDate(new Date());
          gantt.scrollTo(scrollNow - 200, 0);
          $("#modal_date_range").modal('hide');
        });
      });
  
</script>