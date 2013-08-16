<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * FusionInvoice
 * 
 * A free and open source web based invoicing system
 *
 * @package		FusionInvoice
 * @author		Jesse Terry
 * @copyright	Copyright (c) 2012 - 2013, Jesse Terry
 * @license		http://www.fusioninvoice.com/support/page/license-agreement
 * @link		http://www.fusioninvoice.com
 * 
 */

class Guest extends Guest_Controller {

    public function index()
    {
        $this->load->model('invoices/mdl_invoices');
        $this->load->model('payments/mdl_payments');
        $this->load->model('quotes/mdl_quotes');

        $this->layout->set(
            array(
                'open_invoices'    => $this->mdl_invoices->is_open()->where_in('fi_invoices.client_id', $this->user_clients)->limit(10)->get()->result(),
                'overdue_invoices' => $this->mdl_invoices->is_overdue()->where_in('fi_invoices.client_id', $this->user_clients)->limit(10)->limit(5)->get()->result(),
                'recent_payments'  => $this->mdl_payments->where_in('fi_invoices.client_id', $this->user_clients)->limit(10)->order_by('payment_date DESC')->limit(5)->get()->result(),
                'open_quotes'      => $this->mdl_quotes->is_open()->where_in('fi_quotes.client_id', $this->user_clients)->limit(10)->get()->result()
            )
        );

        $this->layout->buffer('content', 'guest/index');
        $this->layout->render('layout_guest');
    }

}

?>