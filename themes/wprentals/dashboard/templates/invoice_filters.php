<div class="invoice_filters">
    <div class="col-md-3">
        <input type="text" id="invoice_start_date" class="form-control" name="invoice_start_date" placeholder="<?php esc_html_e( 'from date','wprentals');?>">
    </div>

    <div class="col-md-3">
        <input type="text" id="invoice_end_date" class="form-control"  name="invoice_end_date" placeholder="<?php esc_html_e( 'to date','wprentals');?>">
    </div>


    <div class="col-md-3">
        <select id="invoice_type" name="invoice_type" class="form-control">
            <option value="Upgrade to Featured"><?php esc_html_e( 'Upgrade to Featured','wprentals');?></option>
            <option value="Publish Listing with Featured"><?php esc_html_e( 'Publish Listing with Featured','wprentals');?></option>
            <option value="Package"><?php esc_html_e( 'Package','wprentals');?></option>
            <option value="Listing"><?php esc_html_e( 'Listing','wprentals');?></option>
            <option value="Reservation fee" selected="selected"><?php esc_html_e( 'Reservation fee','wprentals');?></option>
        </select>
    </div>

    <div class="col-md-3">
        <select id="invoice_status" name="invoice_status" class="form-control">
            <option value=""><?php esc_html_e( 'Any','wprentals');?></option>
            <option value="confirmed"><?php esc_html_e( 'confirmed','wprentals');?></option>
            <option value="issued"><?php esc_html_e( 'issued','wprentals');?></option>
        </select>

    </div>

</div>

<div class="invoices_explanation"><?php esc_html_e( 'Reservation fees filter applies only to the invoices issued by you!','wprentals');?></div>

<div class="col-md-12 invoice_totals">
<strong>
  <?php esc_html_e( 'Total Invoices Confirmed: ','wprentals');?>
</strong>

<span id="invoice_confirmed">
  <?php wpestate_show_price_custom_invoice($total_confirmed);?>
</span>

<strong>
  <?php esc_html_e( 'Total Invoices Issued: ','wprentals');?></strong>
  <span id="invoice_issued">
    <?php wpestate_show_price_custom_invoice($total_issued);?>
  </span>
</div>
