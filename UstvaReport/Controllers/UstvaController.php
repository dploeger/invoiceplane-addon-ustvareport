<?php

namespace Addons\UstvaReport\Controllers;

use FI\Http\Controllers\Controller;
use Addons\UstvaReport\Reports\UstvaReport;
use FI\Modules\Reports\Requests\DateRangeRequest;
use FI\Support\PDF\PDFFactory;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;


class UstvaController extends Controller {

    private $report;

    public function __construct(UstvaReport $report)
    {
        $this->report = $report;
    }

    public function index() {
        return view('ustva.options', ['companyProfiles' => ['' => trans('fi.all_company_profiles')] + CompanyProfile::getList()]);
    }

    public function validateOptions(DateRangeRequest $request)
    {

    }

    public function html()
    {
        $results = $this->report->getResults(
            request('from_date'),
            request('to_date'),
            request('company_profile_id'),
            request('exclude_unpaid_invoices')
        );

        return view('ustva.output')
            ->with('results', $results);
    }

    public function pdf()
    {
        $pdf = PDFFactory::create();

        $results = $this->report->getResults(
            request('from_date'),
            request('to_date'),
            request('company_profile_id'),
            request('exclude_unpaid_invoices')
        );

        $html = view('ustva.output')
            ->with('results', $results)->render();

        $pdf->download($html, trans('UstvaReport::common.filename') . '.pdf');
    }
}