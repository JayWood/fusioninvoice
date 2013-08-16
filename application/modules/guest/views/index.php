<div class="headerbar">
	<h1><?php echo lang('dashboard'); ?></h1>
</div>

<div class="content">
	<div class="container-fluid">

		<div class="row-fluid">

			<div class="span6">
				
				<div id="open_invoices" class="widget">

					<div class="widget-title">
						<h5><i class="icon-file"></i> <?php echo lang('open_invoices'); ?></h5>
					</div>

                    <?php if ($open_invoices) { ?>
					<table class="table table-striped no-margin">
						<thead>
							<tr>
								<th><?php echo lang('due_date'); ?></th>
								<th><?php echo lang('invoice'); ?></th>
								<th style="text-align: right;"><?php echo lang('total'); ?></th>
                                <th style="text-align: right;"><?php echo lang('paid'); ?></th>
                                <th style="text-align: right;"><?php echo lang('balance'); ?></th>
                                <th style="text-align: center;"><?php echo lang('pdf'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($open_invoices as $open_invoice) { ?>
							<tr>
								<td><?php echo date_from_mysql($open_invoice->invoice_date_due); ?></td>
								<td><?php echo anchor('guest/invoices/view/' . $open_invoice->invoice_id, $open_invoice->invoice_number, array('title'=>lang('view'))); ?></td>
								<td style="text-align: right;"><?php echo format_currency($open_invoice->invoice_total); ?></td>
                                <td style="text-align: right;"><?php echo format_currency($open_invoice->invoice_paid); ?></td>
                                <td style="text-align: right;"><?php echo format_currency($open_invoice->invoice_balance); ?></td>
                                <td style="text-align: center;"><a href="<?php echo site_url('guest/invoices/generate_pdf/' . $open_invoice->invoice_id); ?>" title="<?php echo lang('download_pdf'); ?>"><i class="icon-print"></i></a></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
                    <?php } else { ?>
                        <p class="padded"><?php echo lang('no_records'); ?></p>
                    <?php } ?>

				</div>
                
				<div id="recent_payments" class="widget">

					<div class="widget-title">
						<h5><i class="icon-ok"></i> <?php echo lang('recent_payments'); ?></h5>
					</div>

					<table class="table table-striped no-margin">
						<thead>
							<tr>
								<th><?php echo lang('date'); ?></th>
								<th><?php echo lang('invoice'); ?></th>
                                <th><?php echo lang('payment_method'); ?></th>
								<th style="text-align: right;"><?php echo lang('amount'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($recent_payments as $recent_payment) { ?>
							<tr>
								<td><?php echo date_from_mysql($recent_payment->payment_date); ?></td>
								<td><?php echo anchor('guest/invoices/view/' . $recent_payment->invoice_id, $recent_payment->invoice_number, array('title'=>lang('view'))); ?></td>
                                <td><?php echo $recent_payment->payment_method_name; ?></td>
								<td style="text-align: right;"><?php echo format_currency($recent_payment->payment_amount); ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					
				</div>

			</div>

			<div class="span6">
				
				<div id="overdue_invoices" class="widget">

					<div class="widget-title">
						<h5><i class="icon-file"></i> <?php echo lang('overdue_invoices'); ?></h5>
					</div>

                    <?php if ($overdue_invoices) { ?>
					<table class="table table-striped no-margin">
						<thead>
							<tr>
								<th><?php echo lang('due_date'); ?></th>
								<th><?php echo lang('invoice'); ?></th>
								<th style="text-align: right;"><?php echo lang('total'); ?></th>
                                <th style="text-align: right;"><?php echo lang('paid'); ?></th>
                                <th style="text-align: right;"><?php echo lang('balance'); ?></th>
                                <th style="text-align: center;"><?php echo lang('pdf'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($overdue_invoices as $overdue_invoice) { ?>
							<tr>
								<td><?php echo date_from_mysql($overdue_invoice->invoice_date_due); ?></td>
								<td><?php echo anchor('guest/invoices/view/' . $overdue_invoice->invoice_id, $overdue_invoice->invoice_number, array('title'=>lang('view'))); ?></td>
								<td style="text-align: right;"><?php echo format_currency($overdue_invoice->invoice_total); ?></td>
                                <td style="text-align: right;"><?php echo format_currency($overdue_invoice->invoice_paid); ?></td>
                                <td style="text-align: right;"><?php echo format_currency($overdue_invoice->invoice_balance); ?></td>
                                <td style="text-align: center;"><a href="<?php echo site_url('guest/invoices/generate_pdf/' . $overdue_invoice->invoice_id); ?>" title="<?php echo lang('download_pdf'); ?>"><i class="icon-print"></i></a></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
                    <?php } else { ?>
                    <p class="padded"><?php echo lang('no_records'); ?></p>
                    <?php } ?>

				</div>
                
				<div id="open_invoices" class="widget">

					<div class="widget-title">
						<h5><i class="icon-file"></i> <?php echo lang('open_quotes'); ?></h5>
					</div>

                    <?php if ($open_quotes) { ?>
					<table class="table table-striped no-margin">
						<thead>
							<tr>
								<th><?php echo lang('quote_date'); ?></th>
                                <th><?php echo lang('expires'); ?></th>
								<th><?php echo lang('quote'); ?></th>
								<th style="text-align: right;"><?php echo lang('amount'); ?></th>
                                <th style="text-align: center;"><?php echo lang('pdf'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($open_quotes as $open_quote) { ?>
							<tr>
								<td><?php echo date_from_mysql($open_quote->quote_date_created); ?></td>
                                <td><?php echo date_from_mysql($open_quote->quote_date_expires); ?></td>
								<td><?php echo anchor('guest/quotes/view/' . $open_quote->quote_id, $open_quote->quote_number, array('title'=>lang('view'))); ?></td>
								<td style="text-align: right;"><?php echo format_currency($open_quote->quote_total); ?></td>
                                <td style="text-align: center;"><a href="<?php echo site_url('guest/quotes/generate_pdf/' . $open_quote->quote_id); ?>" title="<?php echo lang('download_pdf'); ?>"><i class="icon-print"></i></a></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
                    <?php } else { ?>
                        <p class="padded"><?php echo lang('no_records'); ?></p>
                    <?php } ?>

				</div>

			</div>

		</div>

	</div>
</div>