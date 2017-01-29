<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" id="button-shipping" form="form-invoice" formaction="<?php echo $shipping; ?>" formtarget="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></button>
        <button type="submit" id="button-invoice" form="form-invoice" formaction="<?php echo $invoice; ?>" formtarget="_blank" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" id="button-delete" form="form-invoice" formaction="<?php echo $delete; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-invoice-id"><?php echo $entry_invoice_id; ?></label>
                <input type="text" name="filter_invoice_id" value="<?php echo $filter_invoice_id; ?>" placeholder="<?php echo $entry_invoice_id; ?>" id="input-invoice-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-invoice-status"><?php echo $entry_invoice_status; ?></label>
                <select name="filter_invoice_status" id="input-invoice-status" class="form-control">
                  <option value="*" selected="selected">All</option>
                  <?php foreach ($invoice_statuses as $invoice_status) { ?>
                  <option value="<?php echo $invoice_status['invoice_status_id']; ?>"><?php echo $invoice_status['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-expire"><?php echo $entry_date_expire; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_expire" value="<?php echo $filter_date_expire; ?>" placeholder="<?php echo $entry_date_expire; ?>" data-date-format="YYYY-MM-DD" id="input-date-expire" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form method="post" action="" enctype="multipart/form-data" id="form-invoice">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-right"><?php if ($sort == 'o.invoice_id') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($invoice); ?>"><?php echo $column_invoice_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_invoice_id; ?></a>
                    <?php } ?></td>

                  <td class="text-right"><?php if ($sort == 'o.invoice_number') { ?>
                    <a href="<?php echo $sort_invoice_number; ?>" class="<?php echo strtolower($invoice); ?>"><?php echo $column_invoice_number; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_invoice_number; ?>"><?php echo $column_invoice_number; ?></a>
                    <?php } ?></td>


                  <td class="text-left"><?php if ($sort == 'customer') { ?>
                    <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($invoice); ?>"><?php echo $column_customer; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'invoice_status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($invoice); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'o.total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($invoice); ?>"><?php echo $column_total; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($invoice); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_expire') { ?>
                    <a href="<?php echo $sort_date_expire; ?>" class="<?php echo strtolower($invoice); ?>"><?php echo $column_date_expire; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_expire; ?>"><?php echo $column_date_expire; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_payed') { ?>
                    <a href="<?php echo $sort_date_payed; ?>" class="<?php echo strtolower($invoice); ?>"><?php echo $column_date_payed; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_payed; ?>"><?php echo $column_date_payed; ?></a>
                    <?php } ?></td>
                    
                  <td class="text-left">
                    <?php echo $column_fact_period; ?>
                  </td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($invoices) { ?>
                <?php foreach ($invoices as $invoice) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($invoice['invoice_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $invoice['invoice_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $invoice['invoice_id']; ?>" />
                    <?php } ?>
                    <input type="hidden" name="shipping_code[]" value="<?php echo $invoice['shipping_code']; ?>" /></td>
                  <td class="text-right"><?php echo $invoice['invoice_id']; ?></td>
                  <td class="text-right"><?php echo $invoice['invoice_number']; ?></td>
                  <td class="text-left"><?php echo $invoice['customer']; ?></td>
                  <td class="text-left"><?php echo $invoice['invoice_status']; ?></td>
                  <td class="text-right"><?php echo $invoice['total']; ?></td>
                  <td class="text-left"><?php echo $invoice['date_added']; ?></td>
                  <td class="text-left"><?php echo $invoice['date_expire']; ?></td>
                  <td class="text-left"><?php echo $invoice['date_payed']; ?></td>
                  <td class="text-left"><?php echo $invoice['fact_period']; ?></td>
                  <td class="text-right"><a href="<?php echo $invoice['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a> <a href="<?php echo $invoice['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=sale/invoice&token=<?php echo $token; ?>';

	var filter_invoice_id = $('input[name=\'filter_invoice_id\']').val();

	if (filter_invoice_id) {
		url += '&filter_invoice_id=' + encodeURIComponent(filter_invoice_id);
	}

	var filter_customer = $('input[name=\'filter_customer\']').val();
        //console.log('Filter customer ' + filter_customer);


        var filter_customer_id = $('a:contains("' +filter_customer + '")').parent().data('value');

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer) + '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

	var filter_invoice_status = $('select[name=\'filter_invoice_status\']').val();

	if (filter_invoice_status != '*') {
		url += '&filter_invoice_status=' + encodeURIComponent(filter_invoice_status);
	}

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_date_expire = $('input[name=\'filter_date_expire\']').val();

	if (filter_date_expire) {
		url += '&filter_date_expire=' + encodeURIComponent(filter_date_expire);
	}

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['label']);
	}
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name^=\'selected\']').on('change', function() {
	$('#button-shipping, #button-invoice').prop('disabled', true);

	var selected = $('input[name^=\'selected\']:checked');

	if (selected.length) {
		$('#button-invoice').prop('disabled', false);
	}

	for (i = 0; i < selected.length; i++) {
		if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
			$('#button-shipping').prop('disabled', false);

			break;
		}
	}
});

$('#button-shipping, #button-invoice').prop('disabled', true);

$('input[name^=\'selected\']:first').trigger('change');

// IE and Edge fix!
$('#button-shipping, #button-invoice').on('click', function(e) {
	$('#form-invoice').attr('action', this.getAttribute('formAction'));
});

$('#button-delete').on('click', function(e) {
	$('#form-invoice').attr('action', this.getAttribute('formAction'));
	
	if (confirm('<?php echo $text_confirm; ?>')) {
		$('#form-invoice').submit();
	} else {
		return false;
	}
});
//--></script> 
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?> 
