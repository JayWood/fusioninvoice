<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * FusionInvoice
 * 
 * A free and open source web based invoicing system
 *
 * @package		FusionInvoice
 * @author		Omar Orellana
 * @copyright	Copyright (c) 2012 - 2013, Jesse Terry
 * @license		http://www.fusioninvoice.com/support/page/license-agreement
 * @link		http://www.fusioninvoice.com
 * 
 */

class Calendar extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('invoices/mdl_invoices');
    }
    
    public function get_jquery_invoices($status = 'open')
    {

        switch ($status)
        {
            case 'open':
                $invoices = $this->mdl_invoices->is_open()->get()->result();
                break;
            case 'closed':
                $invoices = $this->mdl_invoices->is_closed()->get()->result();
                break;
            case 'overdue':
                $invoices = $this->mdl_invoices->is_overdue()->get()->result();
                break;
        }

        $inv_array = array();

        foreach ($invoices as $invoice)
        {

            $inv_array[] = array(
                'id' => $invoice->invoice_id,
                'title' => $invoice->client_name . ' (' . format_currency($invoice->invoice_total) . ')',
                'start' => $invoice->invoice_date_due,
                'url' => site_url('invoices/view/' . $invoice->invoice_id ),
            );
        }

        echo json_encode($inv_array);
    }

}
