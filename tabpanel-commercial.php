<div role="tabpanel" class="tab-pane animated fadeIn" id="commercial" aria-labelledby="commercial-tab">
  
<div class="row">
      <div class="col-md-10" style="padding: 50px;">
         <h4 style="color: <?=$_SESSION['user']['opacity_font']?>">Commercial management</h4>
    <p style="color: <?=$_SESSION['user']['opacity_font']?>">Task pricing</p>

        <hr>
        <table id="table_pricing" class="table table-sm">
                  <thead>
                    <tr>
                      <th>Task Name</th>
                      <th>Item Name</th>
                      <th>Quantity</th>
                      <th>Unit</th>
                      <th>Sum £</th>
                      <th></th> <!-- Edit -->
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
        <div id="total-price"></div>
        <hr>
        <table id="table_SOR" class="table table-sm">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th style="opacity: 0.5">People £</th>
                      <th style="opacity: 0.5">Equipment £</th>
                      <th style="opacity: 0.5">Plant £</th>
                      <th style="opacity: 0.5">Materials £</th>
                      <th>Unit</th>
                      <th>Rate £</th>
                      <th></th> <!-- Edit -->
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
      </div>
      <div class="col-md-2" id="commercial-sidebar">
        <div class="row">
          <button class="add-pricing-item mx-auto huge pulse"><img class="setup" src="img/svg/resource-item.svg"><img class="commercial-locked" src="img/svg/lock-closed.svg" style="display: none;"><h4>Add<br>Item</h4></button>          
        </div>
        <div class="row">
          <div class="card mx-auto">
            <div class="card-body" style="padding-top: 20px;">
              <button id="manage-SOR-items" class="manage-SOR-items" style="margin-bottom: 10px;">Add SOR Item<img class="SOR-locked" src="img/svg/lock-closed.svg" style="display: none;"></button>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>