<div class="modal animated fadeIn" id="modal_pricing_editor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Pricing Editor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="pricing_item_id" value="0">
        <form id="form_pricing_editor">
          <div class="accordion md-accordion" id="accordionPricingEditor" role="tablist" aria-multiselectable="true">
            <div class="card" id="pricing-editor-general-section">
              <a class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordionPricingEditor" href="#collapsePricingEditorGeneral" aria-expanded="false" aria-controls="collapsePricingEditorGeneral">
                <p class="mb-0">General</p>
              </a>
              <div id="collapsePricingEditorGeneral" class="accordion-item-ui collapse" role="tabpanel" aria-labelledby="headingPricingEditorGeneral" data-parent="#accordionPricingEditor">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label>Section (for NRM2 elemental breakdown structure)</label>
                        <select class="mdb-select dropdown-primary" id="pricing_section" style="font-size: 0.8em">
                          <option selected>Select</option>
                          <option value="Preliminaries">Preliminaries</option>
                          <option value="Facilitating Works">Facilitating Works</option>
                          <option value="Substructure">Substructure</option>
                          <option value="Superstructure">Superstructure</option>
                          <option value="Internal Finishes">Internal Finishes</option>
                          <option value="Fittings, Furnishings and Equipment">Fittings, Furnishings and Equipment</option>
                          <option value="Services">Services</option>
                          <option value="External Works">External Works</option>
                          <option value="Risks">Risks</option>
                          <option value="Provisional Sums">Provisional Sums</option>
                          <option value="Credits">Credits</option>
                          <option value="Daywork">Daywork</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label>Reference</label>
                        <input type="text" id="pricing_reference" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label>Description</label>
                        <input type="text" id="pricing_description" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label>Quantity</label>
                        <input type="text" id="pricing_quantity" class="form-control">
                      </div>
                    </div>
                    <div class="col">
                      <div class="md-form">
                        <label>Unit</label>
                        <input type="text" id="pricing_unit" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label>SOR Item</label>
                        <select class="mdb-select dropdown-primary" id="select_SOR_item" style="font-size: 0.8em"></select>

                      </div>
                    </div>
                </div>
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label>Rate £</label>
                        <input disabled type="text" id="pricing_rate" class="form-control">
                      </div>
                    </div>
                    <div class="col">
                      <div class="md-form">
                        <label>Sum £</label>
                        <input disabled type="text" id="pricing_sum" class="form-control">
                      </div>
                    </div>
                  </div>
              </div>
            </div>
            </div></div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="save-pricing-item">Save</button>
      </div>
    </div>
  </div>
</div>




























<div class="modal animated fadeIn" id="modal_SOR_editor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">SOR Editor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="SOR_item_id" value="0">
        <form id="form_SOR_editor">
            <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label>Name</label>
                        <input type="text" id="SOR_name" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label>People £</label>
                        <input type="text" id="SOR_people" name="SOR_people" class="form-control">
                      </div>
                    </div>
                    <div class="col">
                      <div class="md-form">
                        <label>Equipment £</label>
                        <input type="text" id="SOR_equipment" name="SOR_equipment" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label>Plant £</label>
                        <input type="text" id="SOR_plant" name="SOR_plant" class="form-control">
                      </div>
                    </div>
                    <div class="col">
                      <div class="md-form">
                        <label>Materials £</label>
                        <input type="text" id="SOR_materials" name="SOR_materials" class="form-control">
                      </div>
                    </div>
                  </div><div class="row">
                    <div class="col">
                      <div class="md-form">
                        <label>Unit</label>
                        <input type="text" id="SOR_unit" class="form-control">
                      </div>
                    </div>
                    <div class="col">
                      <div class="md-form">
                        <label>Rate £</label>
                        <input disabled type="text" id="SOR_rate" name="SOR_rate" class="form-control">
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="save-SOR-item">Save</button>
      </div>
    </div>
  </div>
</div>