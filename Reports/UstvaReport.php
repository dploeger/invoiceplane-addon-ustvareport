<?php

namespace Addons\UstvaReport\Reports;

use IP\Modules\Expenses\Models\Expense;
use IP\Modules\Invoices\Models\Invoice;
use IP\Support\CurrencyFormatter;
use IP\Support\DateFormatter;
use IP\Support\NumberFormatter;
use IP\Support\Statuses\InvoiceStatuses;
use Illuminate\Support\Carbon;

class UstvaReport
{
    public function getResults($fromDate, $toDate, $companyProfileId = null, $excludeUnpaidInvoices = 0)
    {
        $results = [
            'from_date' => DateFormatter::format($fromDate),
            'to_date' => DateFormatter::format($toDate),
            'field_66' => 0,
            'field_35' => 0,
            'field_36' => 0,
            'field_81' => 0,
            'field_83' => 0,
            'field_86' => 0
        ];

        $invoices = Invoice::with(['client', 'amount', 'items.taxRate', 'items.amount'])
            ->where('invoice_date', '>=', $fromDate)
            ->where('invoice_date', '<=', $toDate)
            ->where('invoice_status_id', '<>', InvoiceStatuses::getStatusId('canceled'));

        $expenses = Expense::with(['custom', 'vendor', 'category'])
            ->where('expense_date', '>=', $fromDate)
            ->where('expense_date', '<=', $toDate);

        if ($companyProfileId) {
            $invoices->where('company_profile_id', $companyProfileId);
            $expenses->where('company_profile_id', $companyProfileId);
        }

        if ($excludeUnpaidInvoices) {
            $invoices->paid();
        }

        $invoices = $invoices->get();
        $expenses = $expenses->get();

        foreach ($invoices as $invoice) {
            foreach ($invoice->items as $item) {
                if ($item->taxRate->percent == '19.000') {
                    $results['field_81'] += $item->amount->subtotal;
                } elseif ($item->taxRate->percent == '7.000') {
                    $results['field_86'] += $item->amount->subtotal;
               } else {
                   $results['field_35'] += $item->amount->subtotal;
                   $results['field_36'] += $item->amount->tax;
               }
            }
        }

        foreach ($expenses as $expense) {
            $results['field_66'] += $expense->amount / ($expense->tax + 100) * $expense->tax;
        }

        $results['field_81'] = intval($results['field_81']);

        $results['field_83'] = $results['field_81'] * 0.19 + $results['field_86'] * 0.07 + $results['field_36'] - $results['field_66'];

        $results['field_83'] = CurrencyFormatter::format($results['field_83']);
        $results['field_81'] = CurrencyFormatter::format($results['field_81']);
        $results['field_86'] = CurrencyFormatter::format($results['field_86']);
        $results['field_66'] = CurrencyFormatter::format($results['field_66']);
        $results['field_35'] = CurrencyFormatter::format($results['field_35']);
        $results['field_36'] = CurrencyFormatter::format($results['field_36']);

        return $results;
    }


}
