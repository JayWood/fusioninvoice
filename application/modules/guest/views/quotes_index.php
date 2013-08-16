<div class="headerbar">

	<h1><?php echo lang('quotes'); ?></h1>
	
	<div class="pull-right">
		<a class="create-quote btn btn-primary" href="#"><i class="icon-plus icon-white"></i> <?php echo lang('new'); ?></a>
	</div>

	<div class="pull-right">
		<?php echo pager(site_url('guest/quotes/status/' . $this->uri->segment(3)), 'mdl_quotes'); ?>
	</div>
    
	<div class="pull-right">
		<ul class="nav nav-pills index-options">
			<li <?php if ($status == 'open') { ?>class="active"<?php } ?>><a href="<?php echo site_url('guest/quotes/status/open'); ?>"><?php echo lang('open'); ?></a></li>
			<li <?php if ($status == 'invoiced') { ?>class="active"<?php } ?>><a href="<?php echo site_url('guest/quotes/status/invoiced'); ?>"><?php echo lang('invoiced'); ?></a></li>
			<li <?php if ($status == 'expired') { ?>class="active"<?php } ?>><a href="<?php echo site_url('guest/quotes/status/expired'); ?>"><?php echo lang('expired'); ?></a></li>
            <li <?php if ($status == 'all') { ?>class="active"<?php } ?>><a href="<?php echo site_url('guest/quotes/status/all'); ?>"><?php echo lang('all'); ?></a></li>
		</ul>
	</div>

</div>

<div id="filter_results">
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
                <td><a href="<?php echo site_url('guest/quotes/view/' . $quote->quote_id); ?>" title="<?php echo lang('edit'); ?>"><?php echo $quote->quote_number; ?></a></td>
                <td><?php echo date_from_mysql($quote->quote_date_created); ?></td>
                <td><?php echo date_from_mysql($quote->quote_date_expires); ?></td>
                <td><a href="<?php echo site_url('clients/view/' . $quote->client_id); ?>" title="<?php echo lang('view_client'); ?>"><?php echo $quote->client_name; ?></a></td>
                <td><?php echo format_currency($quote->quote_total); ?></td>
                <td>
                    <div class="options btn-group">
                        <a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog"></i> <?php echo lang('options'); ?></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo site_url('guest/quotes/view/' . $quote->quote_id); ?>">
                                    <i class="icon-search"></i> <?php echo lang('view'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('guest/quotes/generate_pdf/' . $quote->quote_id); ?>">
                                    <i class="icon-print"></i> <?php echo lang('download_pdf'); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </tbody>

    </table>
</div>