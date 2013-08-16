<?php

function parse_template($object, $body)
{
    $body = str_replace('{{{client_name}}}', $object->client_name, $body);
    $body = str_replace('{{{invoice_guest_url}}}', site_url('guest/view/invoice/' . $object->invoice_url_key), $body);
    $body = str_replace('{{{user_name}}}', $object->user_name, $body);
    $body = str_replace('{{{user_company}}}', $object->user_company, $body);
    $body = str_replace('{{{user_address_1}}}', $object->user_address_1, $body);
    $body = str_replace('{{{user_address_2}}}', $object->user_address_2, $body);
    $body = str_replace('{{{user_city}}}', $object->user_city, $body);
    $body = str_replace('{{{user_state}}}', $object->user_state, $body);
    $body = str_replace('{{{user_zip}}}', $object->user_zip, $body);
    $body = str_replace('{{{user_country}}}', $object->user_country, $body);
    $body = str_replace('{{{user_phone}}}', $object->user_phone, $body);
    $body = str_replace('{{{user_fax}}}', $object->user_fax, $body);
    $body = str_replace('{{{user_mobile}}}', $object->user_mobile, $body);
    $body = str_replace('{{{user_email}}}', $object->user_email, $body);
    $body = str_replace('{{{user_web}}}', $object->user_web, $body);
    $body = str_replace('{{{client_address_1}}}', $object->client_address_1, $body);
    $body = str_replace('{{{client_address_2}}}', $object->client_address_2, $body);
    $body = str_replace('{{{client_city}}}', $object->client_city, $body);
    $body = str_replace('{{{client_state}}}', $object->client_state, $body);
    $body = str_replace('{{{client_zip}}}', $object->client_zip, $body);
    $body = str_replace('{{{client_country}}}', $object->client_country, $body);

    if (!isset($object->quote_id))
    {
        $body = str_replace('{{{invoice_number}}}', $object->invoice_number, $body);
        $body = str_replace('{{{invoice_date_due}}}', date_from_mysql($object->invoice_date_due, TRUE), $body);
        $body = str_replace('{{{invoice_date_created}}}', date_from_mysql($object->invoice_date_created, TRUE), $body);
        $body = str_replace('{{{invoice_terms}}}', $object->invoice_terms, $body);
        $body = str_replace('{{{invoice_total}}}', format_currency($object->invoice_total), $body);
        $body = str_replace('{{{invoice_paid}}}', format_currency($object->invoice_paid), $body);
        $body = str_replace('{{{invoice_balance}}}', format_currency($object->invoice_balance), $body);
        $body = str_replace('{{{invoice_status}}}', $object->invoice_status, $body);
    }

    else
    {
        $body = str_replace('{{{quote_total}}}', format_currency($object->quote_total), $body);
        $body = str_replace('{{{quote_date_created}}}', date_from_mysql($object->quote_date_created, TRUE), $body);
        $body = str_replace('{{{quote_date_expires}}}', date_from_mysql($object->quote_date_expires, TRUE), $body);
        $body = str_replace('{{{quote_number}}}', $object->quote_number, $body);
        $body = str_replace('{{{quote_guest_url}}}', site_url('guest/view/quote/' . $object->quote_url_key), $body);
    }

    return $body;
}

?>