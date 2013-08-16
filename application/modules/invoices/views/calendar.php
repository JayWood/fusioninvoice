<div class="headerbar">

    <h1><?php echo lang('invoices'); ?></h1>

    <div class="pull-right">
        <a class="create-invoice btn btn-primary" href="#"><i class="icon-plus icon-white"></i> <?php echo lang('new'); ?></a>
    </div>

    <div class="pull-right">
        <a href="<?php echo site_url('invoices/index'); ?>" class="btn btn-info" title="<?php echo lang('list_view'); ?>"><i class="icon-list icon-white"></i></a>
    </div>

</div>

<div id="filter_results">
    <div class="content">
        <div class="calendar container-fluid">

            <div class="widget-content">
                <?php echo $calendar; ?>
            </div>

        </div>
    </div>
</div>
