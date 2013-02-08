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
 * @license		http://www.fusioninvoice.com/license.txt
 * @link		http://www.fusioninvoice.
 * 
 */

class Clients extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('mdl_clients');
    }

    public function index()
    {
        // Display active clients by default
        redirect('clients/status/active');
    }

    public function status($status = 'active')
    {
        $clients = $this->mdl_clients->with_totals()->paginate()->result();

        if (is_numeric(array_search($status, array('active', 'inactive'))))
        {
            $function = 'is_' . $status;
            $this->layout->set('records', $this->mdl_clients->with_totals()->$function()->paginate()->result());
        }
        else
        {
            $this->layout->set('records', $this->mdl_clients->with_totals()->paginate()->result());
        }

        $this->layout->set(
            array(
                'filter_display'     => TRUE,
                'filter_placeholder' => lang('filter_clients'),
                'filter_method'      => 'filter_clients'
            )
        );

        $this->layout->buffer('content', 'clients/index');
        $this->layout->render();
    }

    public function form($id = NULL)
    {
        if ($this->input->post('btn_cancel'))
        {
            redirect('clients');
        }

        if ($this->mdl_clients->run_validation())
        {
            $id = $this->mdl_clients->save($id);

            $this->load->model('custom_fields/mdl_client_custom');
            
            $this->mdl_client_custom->save_custom($id, $this->input->post('custom'));

            redirect('clients/view/' . $id);
        }

        if ($id and !$this->input->post('btn_submit'))
        {
            $this->mdl_clients->prep_form($id);

            $this->load->model('custom_fields/mdl_client_custom');

            $client_custom = $this->mdl_client_custom->where('client_id', $id)->get();

            if ($client_custom->num_rows())
            {
                $client_custom = $client_custom->row();

                unset($client_custom->client_id, $client_custom->client_custom_id);

                foreach ($client_custom as $key => $val)
                {
                    $this->mdl_clients->set_form_value('custom[' . $key . ']', $val);
                }
            }
        }
        elseif ($this->input->post('btn_submit'))
        {
            foreach ($this->input->post('custom') as $key=>$val)
            {
                $this->mdl_clients->set_form_value('custom[' . $key . ']', $val);
            }
        }
        
        $this->load->model('custom_fields/mdl_custom_fields');

        $this->layout->set('custom_fields', $this->mdl_custom_fields->by_table('fi_client_custom')->get()->result());
        $this->layout->buffer('content', 'clients/form');
        $this->layout->render();
    }

    public function view($client_id)
    {
        $this->load->model('clients/mdl_client_notes');
        $this->load->model('invoices/mdl_invoices');
        $this->load->model('quotes/mdl_quotes');
        $this->load->model('custom_fields/mdl_custom_fields');

        $this->layout->set(
            array(
                'client'        => $this->mdl_clients->with_totals()->where('fi_clients.client_id', $client_id)->get()->row(),
                'client_notes'  => $this->mdl_client_notes->where('client_id', $client_id)->get()->result(),
                'invoices'      => $this->mdl_invoices->by_client($client_id)->limit(10)->get()->result(),
                'quotes'        => $this->mdl_quotes->is_open()->by_client($client_id)->limit(10)->get()->result(),
                'custom_fields' => $this->mdl_custom_fields->by_table('fi_client_custom')->get()->result()
            )
        );

        $this->layout->buffer(
            array(
                array('invoice_table', 'invoices/partial_invoice_table'),
                array('quote_table', 'quotes/partial_quote_table'),
                array('partial_notes', 'clients/partial_notes'),
                array('content', 'clients/view')
            )
        );

        $this->layout->render();
    }

    public function delete($client_id)
    {
        $this->mdl_clients->delete($client_id);
        redirect('clients');
    }

}

?>