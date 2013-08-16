
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/default/fullcalendar/fullcalendar.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/default/fullcalendar/fullcalendar.print.css" media="print" />

<script defer type="text/javascript" src="<?php echo base_url(); ?>assets/default/fullcalendar/fullcalendar.min.js"></script>
<script defer type="text/javascript" src="<?php echo base_url(); ?>assets/default/fullcalendar/gcal.js"></script>

<script type='text/javascript'>

    $(document).ready(function() {

        $('#calendar').fullCalendar({
        
            header: {
                left: 'prev, next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            buttonText: {
  				today: '<?php echo lang('calendar_today'); ?>',
				day: '<?php echo lang('calendar_day'); ?>',
				week:'<?php echo lang('calendar_week'); ?>',
				month:'<?php echo lang('calendar_month'); ?>'              
            },
            monthNames: [<?php echo "'" . implode("','", lang('calendar_months')) . "'"; ?>],
            monthNamesShort: [<?php echo "'" . implode("','", lang('calendar_months_short')) . "'"; ?>],
            dayNames: [<?php echo "'" . implode("','", lang('calendar_days')) . "'"; ?>],
            daysShort: [<?php echo "'" . implode("','", lang('calendar_days_short')) . "'"; ?>],
            editable: false,
            eventSources: [
              {
                  url: '<?php echo site_url('calendar/get_jquery_invoices/overdue'); ?>',
                  color: 'red',
                  textColor: 'white'
              },
              {
                  url: '<?php echo site_url('calendar/get_jquery_invoices/open'); ?>',
                  color: 'green',
                  textColor: 'white',
              },
              
            ],
            
        
        });
    
    });
</script>

<style type='text/css'>

    #loading {
        position: absolute;
        top: 5px;
        right: 5px;
    }

    #calendar {
        width: 100%;
        margin: 0 auto !important;
    }
    #calendar table td:first-child { 
        padding-left: 0px !important; 
    }
    
</style>

<div id='loading' style='display:none'>loading...</div>
<div id='calendar'></div>


