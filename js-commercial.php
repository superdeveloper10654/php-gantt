<script>
  $("#task_commercial_select").select2({
    closeOnSelect: true,
    placeholder: "Select a task",
    multiple: false
  });
  $('#task_commercial_select').on("select2:select", function(e) {

    $.getJSON("beta.ajax.php?action=get_tasks_commercial&id=" + e.params.data.id, function(data) {
      window.ibex_gantt_config.task_commercial = data.task;
      var task = data.task;
      var total_price = ('123123');
      $.getJSON("beta.ajax.php?action=update_task_price&id=" + e.params.data.id + "&total_price=" + total_price, function(data) {});
      $("#total-price").html('Total price: £' + total_price);


      // HUGE SIDEBAR BUTTON
      $(document).on('click', '.manage-SOR-items', function(e) {
        $("#SOR_item_id").val('0');
        $("#modal_SOR_editor").modal('show');
      });

      $(document).on('click', '.add-SOR-item', function(e) {
        $("#SOR_item_id").val('0');
        $("#SOR_name").val('');
        $("#SOR_people").val('');
        $("#SOR_equipment").val('');
        $("#SOR_plant").val('');
        $("#SOR_materials").val('');
        $("#SOR_unit").val('');
        $("#SOR_rate").val('');
        $("#modal_SOR_editor").modal('show');
      });









      $(document).on('click', '.save-SOR-item', function(e) {
        $("#table_SOR > tbody").empty();
        $.getJSON("beta.ajax.php?action=update_SOR_detail&id=" + $("#SOR_item_id").val() + "&SOR_name=" + $("#SOR_name").val() + "&SOR_people=" + $("#SOR_people").val() + "&SOR_equipment=" + $("#SOR_equipment").val() + "&SOR_plant=" + $("#SOR_plant").val() + "&SOR_materials=" + $("#SOR_materials").val() + "&SOR_unit=" + $("#SOR_unit").val() + "&SOR_rate=" + $("#SOR_rate").val(), function(data) {
          $.each(data.SOR_items, function(index) {
            var SORId = data.SOR_items[index].id;
            var SORname = data.SOR_items[index].name;
            var SORpeople = data.SOR_items[index].people;
            var SORequipment = data.SOR_items[index].equipment;
            var SORplant = data.SOR_items[index].plant;
            var SORmaterials = data.SOR_items[index].materials;
            var SORunit = data.SOR_items[index].unit;
            var SORrate = data.SOR_items[index].rate;
            $("#table_SOR > tbody").append('<tr><td>' + SORname + '</td><td style="opacity: 0.5;">' + SORpeople + '</td><td style="opacity: 0.5;">' + SORequipment + '</td><td style="opacity: 0.5;">' + SORplant + '</td><td style="opacity: 0.5;">' + SORmaterials + '</td><td>' + SORunit + '</td><td>' + SORrate + '</td><td><span aria-hidden="true"><img src="img/svg/edit.svg" class="edit-SOR-item" data-id="' + data.SOR_items[index].id + '"></span></td></tr>');
          });
        });
        $("#modal_SOR_editor").modal('hide');
      });

      $.getJSON("beta.ajax.php?action=get_SOR_detail", function(data) {
        $.each(data.SOR_items, function(index) {
          var SORId = data.SOR_items[index].id;
          var SORname = data.SOR_items[index].name;
          var SORpeople = data.SOR_items[index].people;
          var SORequipment = data.SOR_items[index].equipment;
          var SORplant = data.SOR_items[index].plant;
          var SORmaterials = data.SOR_items[index].materials;
          var SORunit = data.SOR_items[index].unit;
          var SORrate = data.SOR_items[index].rate;
          $("#table_SOR > tbody").append('<tr><td>' + SORname + '</td><td style="opacity: 0.5;">' + SORpeople + '</td><td style="opacity: 0.5;">' + SORequipment + '</td><td style="opacity: 0.5;">' + SORplant + '</td><td style="opacity: 0.5;">' + SORmaterials + '</td><td>' + SORunit + '</td><td>' + SORrate + '</td><td><span aria-hidden="true"><img src="img/svg/edit.svg" class="edit-SOR-item" data-id="' + data.SOR_items[index].id + '"></span></td></tr>');
        });
      });

      $(document).on('click', '.edit-SOR-item', function(e) {
        var _this = $(this);
        $.getJSON("beta.ajax.php?action=get_SOR_detail&id=" + $(this).data("id"), function(data) {
          $("#SOR_name").val(data.SOR_item.name).trigger("change");
          $("#SOR_people").val(data.SOR_item.people).trigger("change");
          $("#SOR_equipment").val(data.SOR_item.equipment).trigger("change");
          $("#SOR_plant").val(data.SOR_item.plant).trigger("change");
          $("#SOR_materials").val(data.SOR_item.materials).trigger("change");
          $("#SOR_unit").val(data.SOR_item.unit).trigger("change");
          var a = parseInt($('input[name=SOR_people]').val());
          var b = parseInt($('input[name=SOR_equipment]').val());
          var c = parseInt($('input[name=SOR_plant]').val());
          var d = parseInt($('input[name=SOR_materials]').val());
          var SORrate = a + b + c + d;
         // $("#SOR_people").val() + $("#SOR_equipment").val() + $("#SOR_plant").val() + $("#SOR_materials").val();
          $("#SOR_rate").val(SORrate).trigger("change");
          $("#SOR_item_id").val(_this.data("id"));
          $("#modal_SOR_editor").modal('show');
        });
      });

      function getSORitems() {
        $("#table_SOR > tbody").empty();
        $.getJSON("beta.ajax.php?action=get_SOR_items", function(data) {
          $.each(data.SOR_items, function(index) {
            var SORId = data.SOR_items[index].id;
            var SORname = data.SOR_items[index].name;
            var SORpeople = data.SOR_items[index].people;
            var SORequipment = data.SOR_items[index].equipment;
            var SORplant = data.SOR_items[index].plant;
            var SORmaterials = data.SOR_items[index].materials;
            var SORunit = data.SOR_items[index].unit;
            var SORrate = data.SOR_items[index].rate;
            $("#table_SOR > tbody").append('<tr><td>' + SORname + '</td><td style="opacity: 0.5;">' + SORpeople + '</td><td style="opacity: 0.5;">' + SORequipment + '</td><td style="opacity: 0.5;">' + SORplant + '</td><td style="opacity: 0.5;">' + SORmaterials + '</td><td>' + SORunit + '</td><td>' + SORrate + '</td><td><span aria-hidden="true"><img src="img/svg/edit.svg" class="edit-SOR-item" data-id="' + data.SOR_items[index].id + '"></span></td></tr>');
          });
        });
      }
      getSORitems();






      // HUGE SIDEBAR BUTTON
      $(document).on('click', '.add-pricing-item', function(e) {
        $("#pricing_item_id").val('0');
        $("#pricing_section").val('');
        $("#pricing_reference").val('');
        $("#pricing_description").val('');
        $("#pricing_quantity").val('');
        $("#pricing_unit").val('');
        $("#pricing_rate").val('');
        $("#pricing_sum").val('');
        $.each(data.SOR_items, function(index) {
          $('#select_SOR_item').append($('<option>', {
            value: data.SOR_items[index].id,
            text: data.SOR_items[index].name
          }));
        });
        $("#modal_pricing_editor").modal('show');
      });




















      $(document).on('click', '.save-pricing-item', function(e) {
        $("#table_pricing > tbody").empty();
        $.getJSON("beta.ajax.php?action=update_pricing_detail&id=" + $("#pricing_item_id").val() + "&pricing_section=" + $("#pricing_section").val() + "&pricing_reference=" + $("#pricing_reference").val() + "&pricing_description=" + $("#pricing_description").val() + "&pricing_quantity=" + $("#pricing_quantity").val() + "&pricing_unit=" + $("#pricing_unit").val() + "&pricing_rate=" + $("#pricing_rate").val() + "&pricing_sum=" + $("#pricing_sum").val() + "&select_SOR_item=" + $("#select_SOR_item").val(), function(data) {
          $.each(data.pricing_items, function(index) {
            var pricingId = data.pricing_items[index].id;
            var pricingSection = data.pricing_items[index].section;
            var pricingRef = data.pricing_items[index].ref;
            var pricingDes = data.pricing_items[index].description;
            var pricingQty = data.pricing_items[index].quantity;
            var pricingUnit = data.pricing_items[index].unit;
            var pricingRate = data.pricing_items[index].rate;
            var pricingSum = $("#pricing_quantity").val() * $("#pricing_rate").val();
            $("#pricing_sum").val(pricingSum).trigger("change");
            
            
          var pricingSORitem = $("#select_SOR_item").val();
            $("#pricing_SOR_item").val(pricingSORitem).trigger("change");
            
            $("#table_pricing > tbody").append('<tr><td>' + pricingSection + '</td><td>' + pricingRef + '</td><td>' + pricingDes + '</td><td>' + pricingQty + '</td><td>' + pricingUnit + '</td><td>' + pricingRate + '</td><td class="pricing-sum" value="' + pricingSum + '">' + pricingSum + '</td><td><span aria-hidden="true"><img src="img/svg/edit.svg" class="edit-pricing-item" data-id="' + data.pricing_items[index].id + '"></span></td></tr>');
          });
          
          
        });
        $("#modal_pricing_editor").modal('hide');
      });

      $.getJSON("beta.ajax.php?action=get_pricing_detail", function(data) {
        $.each(data.pricing_items, function(index) {
          var pricingId = data.pricing_items[index].id;
          var pricingSection = data.pricing_items[index].section;
          var pricingRef = data.pricing_items[index].ref;
          var pricingDes = data.pricing_items[index].description;
          var pricingQty = data.pricing_items[index].quantity;
          var pricingUnit = data.pricing_items[index].unit;
          var pricingRate = data.pricing_items[index].rate;
          var pricingSum = data.pricing_items[index].sum;
          var SORitem = data.pricing_items[index].SORitem;
          $("#table_pricing > tbody").append('<tr><td>' + pricingSection + '</td><td>' + pricingRef + '</td><td>' + pricingDes + '</td><td>' + pricingQty + '</td><td>' + pricingUnit + '</td><td>' + pricingRate + '</td><td class="pricing-sum" value="' + pricingSum + '">' + pricingSum + '</td><td><span aria-hidden="true"><img src="img/svg/edit.svg" class="edit-pricing-item" data-id="' + data.pricing_items[index].id + '"></span></td></tr>');
        });
      });

      $(document).on('click', '.edit-pricing-item', function(e) {
        var _this = $(this);
        $.getJSON("beta.ajax.php?action=get_pricing_detail&id=" + $(this).data("id"), function(data) {
          $("#pricing_section").val(data.pricing_item.section).trigger("change");
          $("#pricing_reference").val(data.pricing_item.ref).trigger("change");
          $("#pricing_description").val(data.pricing_item.description).trigger("change");
          $("#pricing_quantity").val(data.pricing_item.quantity).trigger("change");
          $("#pricing_unit").val(data.pricing_item.unit).trigger("change");
          $("#pricing_rate").val(data.pricing_item.rate).trigger("change");
          var pricingSum = $("#pricing_quantity").val() * $("#pricing_rate").val();
          $("#pricing_sum").val(pricingSum).trigger("change");
          
          
          var pricingSORitem = $("#select_SOR_item").val();
            $("#pricing_SOR_item").val(pricingSORitem).trigger("change");
          
          $("#pricing_item_id").val(_this.data("id"));
          $("#modal_pricing_editor").modal('show');
        });
      });

      function getPricingItems() {
        $("#table_pricing > tbody").empty();
        $.getJSON("beta.ajax.php?action=get_pricing_items", function(data) {

          $.each(data.client_items, function(index) {
            var pricingId = data.pricing_items[index].id;
            var pricingSection = data.pricing_items[index].section;
            var pricingRef = data.pricing_items[index].ref;
            var pricingDes = data.pricing_items[index].description;
            var pricingQty = data.pricing_items[index].quantity;
            var pricingUnit = data.pricing_items[index].unit;
            var pricingRate = data.pricing_items[index].rate;
            var pricingSum = data.pricing_items[index].sum;
            var SORitem = data.pricing_items[index].SORitem;
            $("#table_pricing > tbody").append('<tr><td>' + pricingSection + '</td><td>' + pricingRef + '</td><td>' + pricingDes + '</td><td>' + pricingQty + '</td><td>' + pricingUnit + '</td><td>' + pricingRate + '</td><td class="pricing-sum" value="' + pricingSum + '">' + pricingSum + '</td><td><span aria-hidden="true"><img src="img/svg/edit.svg" class="edit-pricing-item" data-id="' + data.pricing_items[index].id + '"></span></td></tr>');
          });

        });
      }

      getPricingItems();





























      /*
      function calculateSum() {
      		var sum = 0;
      		$(".pricing-sum").each(function() {
      			var value = $(this).text();
              sum += parseFloat(value);
      		});
      		$("#total-price").html(sum.toFixed(2));S
      	}
            $(calculateSum);
            */








































    });
  });
</script>