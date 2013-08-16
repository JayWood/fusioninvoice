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

class Quotes extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('mdl_quotes');
    }

    public function index()
    {
        // Display open quotes by default
        redirect('quotes/status/open');
    }

    public function status($status = 'open', $page = 0)
    {
        // Determine which group of quotes to load
        switch ($status)
        {
            case 'expired':
                $this->mdl_quotes->is_expired();
                break;
            case 'invoiced':
                $this->mdl_quotes->is_invoiced();
                $this->layout->set('show_invoice_column', TRUE);
                break;
            case 'open':
                $this->mdl_quotes->is_open();
                break;
        }

        $this->mdl_quotes->paginate(site_url('quotes/status/' . $status), $page);
        $quotes = $this->mdl_quotes->result();

        $this->layout->set(
            array(
                'quotes'             => $quotes,
                'status'             => $status,
                'filter_display'     => TRUE,
                'filter_placeholder' => lang('filter_quotes'),
                'filter_method'      => 'filter_quotes'
            )
        );

        $this->layout->buffer('content', 'quotes/index');
        $this->layout->render();
    }

    public function client($client_id, $status = 'open', $page = 0)
    {
        // Determine which group of quotes to load
        switch ($status)
        {
            case 'open':
                $this->mdl_quotes->by_client($client_id)->is_open();
                break;
            case 'closed':
                $this->mdl_quotes->by_client($client_id)->is_closed();
                break;
            case 'overdue':
                $this->mdl_quotes->by_client($client_id)->is_overdue();
                break;
        }

        $this->mdl_quotes->paginate(site_url('quotes/client/' . $client_id . '/' . $status), $page);
        $quotes = $this->mdl_quotes->result();

        $this->layout->set(
            array(
                'client_id'          => $client_id,
                'quotes'             => $quotes,
                'status'             => $status,
                'filter_display'     => TRUE,
                'filter_placeholder' => lang('filter_quotes'),
                'filter_method'      => 'filter_quotes'
            )
        );

        $this->layout->buffer('content', 'quotes/index_client');
        $this->layout->render();
    }

    public function view($quote_id)
    {
        $this->load->model('mdl_quote_items');
        $this->load->model('tax_rates/mdl_tax_rates');
        $this->load->model('mdl_quote_tax_rates');
        $this->load->model('custom_fields/mdl_custom_fields');
        $this->load->model('custom_fields/mdl_quote_custom');

        $quote_custom = $this->mdl_quote_custom->where('quote_id', $quote_id)->get();

        if ($quote_custom->num_rows())
        {
            $quote_custom = $quote_custom->row();

            unset($quote_custom->quote_id, $quote_custom->quote_custom_id);

            foreach ($quote_custom as $key => $val)
            {
                $this->mdl_quotes->set_form_value('custom[' . $key . ']', $val);
            }
        }

        $quote = $this->mdl_quotes->get_by_id($quote_id);

        if (!$quote)
        {
            show_404();
        }

        $this->layout->set(
            array(
                'quote'           => $quote,
                'items'           => $this->mdl_quote_items->where('quote_id', $quote_id)->get()->result(),
                'quote_id'        => $quote_id,
                'tax_rates'       => $this->mdl_tax_rates->get()->result(),
                'quote_tax_rates' => $this->mdl_quote_tax_rates->where('quote_id', $quote_id)->get()->result(),
                'custom_fields'   => $this->mdl_custom_fields->by_table('fi_quote_custom')->get()->result(),
                'custom_js_vars'  => array(
                    'currency_symbol'           => $this->mdl_settings->setting('currency_symbol'),
                    'currency_symbol_placement' => $this->mdl_settings->setting('currency_symbol_placement'),
                    'decimal_point'             => $this->mdl_settings->setting('decimal_point')
                )
            )
        );

        $this->layout->buffer(
            array(
                array('modal_delete_quote', 'quotes/modal_delete_quote'),
                array('modal_add_quote_tax', 'quotes/modal_add_quote_tax'),
                array('content', 'quotes/view')
            )
        );

        $this->layout->render();
    }

    public function calendar()
    {
        $this->layout->buffer(
            array(
                array('calendar', 'calendar/full_calendar'),
                array('content', 'quotes/calendar')
            )
        );

        $this->layout->render();
    }

    public function delete($quote_id)
    {
        // Delete the quote
        $this->mdl_quotes->delete($quote_id);

        // Redirect to quote index
        redirect('quotes/index');
    }

    public function delete_item($quote_id, $item_id)
    {
        // Delete quote item
        $this->load->model('mdl_quote_items');
        $this->mdl_quote_items->delete($item_id);

        // Redirect to quote view
        redirect('quotes/view/' . $quote_id);
    }

    public function generate_pdf($quote_id, $stream = TRUE, $quote_template = NULL)
    {
        $this->load->model('mdl_quote_items');
        $this->load->model('mdl_quote_tax_rates');

        $quote = $this->mdl_quotes->get_by_id($quote_id);

        if (!$quote_template)
        {
            $quote_template = $this->mdl_settings->setting('default_quote_template');
        }

        $data = array(
            'quote'           => $quote,
            'quote_tax_rates' => $this->mdl_quote_tax_rates->where('quote_id', $quote_id)->get()->result(),
            'items'           => $this->mdl_quote_items->where('quote_id', $quote_id)->get()->result(),
            'output_type'     => 'pdf'
        );

        $html = $this->load->view('quote_templates/' . $quote_template, $data, TRUE);

        $this->load->helper('mpdf');

        echo pdf_create($html, lang('quote') . '_' . $quote->quote_number, $stream);
    }

    public function delete_quote_tax($quote_id, $quote_tax_rate_id)
    {
        $this->load->model('mdl_quote_tax_rates');
        $this->mdl_quote_tax_rates->delete($quote_tax_rate_id);

        $this->load->model('mdl_quote_amounts');
        $this->mdl_quote_amounts->calculate($quote_id);

        redirect('quotes/view/' . $quote_id);
    }

    public function recalculate_all_quotes()
    {
        $this->db->select('quote_id');
        $quote_ids = $this->db->get('fi_quotes')->result();

        $this->load->model('mdl_quote_amounts');

        foreach ($quote_ids as $quote_id)
        {
            $this->mdl_quote_amounts->calculate($quote_id->quote_id);
        }
    }

}

?>