<?php echo $this->layout->load_view('layout/alerts'); ?>

<table class="table table-striped">

	<thead>
		<tr>
			<th><?php echo lang('status'); ?></th>
			<th><?php echo lang('quote'); ?></th>
			<th><?php echo lang('created'); ?></th>
			<th><?php echo lang('due_date'); ?></th>
			<th><?php echo lang('client_name'); ?></th>
			<th><?php echo lang('amount'); ?></th>
			<th><?php echo lang('options'); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($quotes as $quote) { ?>
		<tr>
			<td>
				<?php if ($quote->quote_status == 'Open') { ?>
				<span class="label label-success"><?php echo lang('open'); ?></span>
				<?php } elseif ($quote->quote_status == 'Invoiced') { ?>
				<span class="label"><?php echo lang('invoiced'); ?></span>
				<?php } elseif ($quote->quote_status == 'Expired') { ?>
				<span class="label label-important"><?php echo lang('expired'); ?></span> 
				<?php } else { ?>
				<span class="label label-info"><?php echo lang('unknown'); ?></span> 
				<?php } ?>
			</td>
			<td><a href="<?php echo site_url('quotes/view/' . $quote->quote_id); ?>" title="<?php echo lang('edit'); ?>"><?php echo $quote->quote_number; ?></a></td>
			<td><?php echo date_from_mysql($quote->quote_date_created); ?></td>
			<td><?php echo date_from_mysql($quote->quote_date_expires); ?></td>
			<td><a href="<?php echo site_url('clients/view/' . $quote->client_id); ?>" title="<?php echo lang('view_client'); ?>"><?php echo $quote->client_name; ?></a></td>
			<td><?php echo format_currency($quote->quote_total); ?></td>
			<td>
				<div class="options btn-group">
					<a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog"></i> <?php echo lang('options'); ?></a>
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo site_url('quotes/view/' . $quote->quote_id); ?>">
								<i class="icon-pencil"></i> <?php echo lang('edit'); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo site_url('quotes/generate_pdf/' . $quote->quote_id); ?>">
								<i class="icon-print"></i> <?php echo lang('download_pdf'); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo site_url('mailer/quote/' . $quote->quote_id); ?>">
								<i class="icon-envelope"></i> <?php echo lang('send_email'); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo site_url('quotes/delete/' . $quote->quote_id); ?>" onclick="return confirm('<?php echo lang('delete_quote_warning'); ?>');">
								<i class="icon-trash"></i> <?php echo lang('delete'); ?>
							</a>
						</li>
					</ul>
				</div>
			</td>
		</tr>
		<?php } ?>
	</tbody>

</table>