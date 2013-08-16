<script type="text/javascript">

    $(function() {

		<?php
		foreach( $custom_js_vars as $k=>$v )
			echo "var " . $k . " = '" . $v . "';\n";
		?>
		
		function currency_format( number ){
			
			number = number.replace(/\./gi, decimal_point );
			if( currency_symbol_placement == 'after')
				return number + currency_symbol;
			else
				return currency_symbol + number;
		}

		function update_item_row( obj ) {

			var row 			= $( obj ).closest('tr');

			var price 			= parseFloat( $(row).find('[name="item_price"]').val() );
			var quantity 		= parseFloat( $(row).find('[name="item_quantity"]').val() );
			var tax_rate 		= parseFloat( $(row).find('[name="item_tax_rate_id"]').find(':selected').text() );

			if( isNaN(price) )	 	{ price = 0 };
			if( isNaN(quantity) ) 	{ quantity = 0 };
			if( isNaN(tax_rate) ) 	{ tax_rate = 0 };
			
			
			var subtotal 		= (price * quantity).toFixed(2);
			var item_tax_total 	= (price * quantity * tax_rate / 100 ).toFixed(2);
			var item_total 		= (price * quantity * parseFloat(1 + (tax_rate/100))).toFixed(2);

			$(row).find('span[name="subtotal"]').text(  currency_format( subtotal ) );
			$(row).find('span[name="item_tax_total"]').text( currency_format( item_tax_total ) );
			$(row).find('span[name="item_total"]').text( currency_format( item_total ) );

		}

		$('input[name="item_price"]').live('keyup', function() { update_item_row($(this)) });
		$('input[name="item_quantity"]').live('keyup', function() { update_item_row($(this)) });
		$('select[name="item_tax_rate_id"]').live('change', function() { update_item_row($(this)) });

        $('#btn_add_item').click(function() {
            $('#new_item').clone().appendTo('#item_table').removeAttr('id').addClass('item').show();
        });

        <?php if (!$items) { ?>
            $('#new_item').clone().appendTo('#item_table').removeAttr('id').addClass('item').show();
        <?php } ?>
            
        $('#btn_save_quote').click(function() {
            var items = [];
            $('table tr.item').each(function() {
                var row = {};
                $(this).find('input,select,textarea').each(function() {
                    row[$(this).attr('name')] = $(this).val();
                });
                items.push(row);
            });
            $.post("<?php echo site_url('quotes/ajax/save'); ?>", {
                quote_id: <?php echo $quote_id; ?>,
                quote_number: $('#quote_number').val(),
                quote_date_created: $('#quote_date_created').val(),
                quote_date_expires: $('#quote_date_expires').val(),
                items: JSON.stringify(items),
                custom: $('input[name^=custom]').serializeArray()
            },
            function(data) {
                var response = JSON.parse(data);
                if (response.success == '1') {
                    window.location = "<?php echo site_url('quotes/view'); ?>/" + <?php echo $quote_id; ?>;
                }
                else {
                    $('.control-group').removeClass('error');
                    for (var key in response.validation_errors) {
                        $('#' + key).parent().parent().addClass('error');
                    }
                }
            });
        });

        $('#btn_generate_pdf').click(function() {
            window.location = '<?php echo site_url('quotes/generate_pdf/' . $quote_id); ?>';
        });

    });

</script>


<?php echo $modal_delete_quote; ?>
<?php echo $modal_add_quote_tax; ?>

<div class="headerbar">
	<h1><?php echo lang('quote'); ?> #<?php echo $quote->quote_number; ?></h1>

	<div class="pull-right">

		<div class="options btn-group pull-left">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#" style="margin-right: 5px;"><i class="icon-cog"></i> <?php echo lang('options'); ?></a>
			<ul class="dropdown-menu">
				<li><a href="#add-quote-tax" data-toggle="modal"><i class="icon-plus-sign"></i> <?php echo lang('add_quote_tax'); ?></a></li>
				<li><a href="#" id="btn_generate_pdf" data-quote-id="<?php echo $quote_id; ?>"><i class="icon-print"></i> <?php echo lang('download_pdf'); ?></a></li>
				<li><a href="<?php echo site_url('mailer/quote/' . $quote->quote_id); ?>"><i class="icon-envelope"></i> <?php echo lang('send_email'); ?></a></li>
                <li><a href="#" id="btn_quote_to_invoice" data-quote-id="<?php echo $quote_id; ?>"><i class="icon-upload"></i> <?php echo lang('quote_to_invoice'); ?></a></li>
				<li><a href="#" id="btn_copy_quote" data-quote-id="<?php echo $quote_id; ?>"><i class="icon-repeat"></i> <?php echo lang('copy_quote'); ?></a></li>
				<li><a href="#delete-quote" data-toggle="modal"><i class="icon-remove"></i> <?php echo lang('delete'); ?></a></li>
			</ul>
		</div>
		
		<a href="#" class="btn" id="btn_add_item" style="margin-right: 5px;"><i class="icon-plus-sign"></i> <?php echo lang('add_item'); ?></a>
		
		<a href="#" class="btn btn-primary" id="btn_save_quote"><i class="icon-ok icon-white"></i> <?php echo lang('save'); ?></a>
	</div>

</div>

<?php echo $this->layout->load_view('layout/alerts'); ?>

<div class="content">
	
	<form id="quote_form" class="form-horizontal">

		<div class="quote">

			<div class="cf">

				<div class="pull-left">

                    <h2><a href="<?php echo site_url('clients/view/' . $quote->client_id); ?>"><?php echo $quote->client_name; ?></a></h2><br>
					<span>
						<?php echo ($quote->client_address_1) ? $quote->client_address_1 . '<br>' : ''; ?>
						<?php echo ($quote->client_address_2) ? $quote->client_address_2 . '<br>' : ''; ?>
						<?php echo ($quote->client_city) ? $quote->client_city : ''; ?>
						<?php echo ($quote->client_state) ? $quote->client_state : ''; ?>
						<?php echo ($quote->client_zip) ? $quote->client_zip : ''; ?>
						<?php echo ($quote->client_country) ? '<br>' . $quote->client_country : ''; ?>
					</span>
					<br><br>
					<?php if ($quote->client_phone) { ?>
					<span><strong><?php echo lang('phone'); ?>:</strong> <?php echo $quote->client_phone; ?></span><br>
					<?php } ?>
					<?php if ($quote->client_email) { ?>
					<span><strong><?php echo lang('email'); ?>:</strong> <?php echo $quote->client_email; ?></span>
					<?php } ?>

				</div>

				<table style="width: auto" class="pull-right table table-striped table-bordered">
                    
                    <tbody>
                        <tr>
                            <td>
                                <div class="control-group">
                                    <label class="control-label"><?php echo lang('quote'); ?> #</label>
                                    <div class="controls">
                                        <input type="text" id="quote_number" class="input-small" value="<?php echo $quote->quote_number; ?>" style="margin: 0px;">    
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo lang('date'); ?></label>
                                    <div class="controls">
                                        <input type="text" id="quote_date_created" class="input-small" value="<?php echo date_from_mysql($quote->quote_date_created); ?>" style="margin: 0px;">    
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo lang('expires'); ?></label>
                                    <div class="controls">
                                        <input type="text" id="quote_date_expires" class="input-small" value="<?php echo date_from_mysql($quote->quote_date_expires); ?>" style="margin: 0px;">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>

				</table>

			</div>

			<?php $this->layout->load_view('quotes/partial_item_table'); ?>
            
            <?php foreach ($custom_fields as $custom_field) { ?>
            <p><strong><?php echo $custom_field->custom_field_label; ?></strong></p>
                    <input type="text" name="custom[<?php echo $custom_field->custom_field_column; ?>]" id="<?php echo $custom_field->custom_field_column; ?>" value="<?php echo $this->mdl_quotes->form_value('custom[' . $custom_field->custom_field_column . ']'); ?>">
            <?php } ?>

            <p class="padded"><?php echo lang('guest_url'); ?>: <?php echo auto_link(site_url('guest/view/quote/' . $quote->quote_url_key)); ?></p>
            
		</div>
		
	</form>

</div>