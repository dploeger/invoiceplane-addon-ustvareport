<?php

namespace Addons\UstvaReport\Controllers;

use IP\Http\Controllers\Controller;
use Addons\UstvaReport\Reports\UstvaReport;
use IP\Modules\Reports\Requests\DateRangeRequest;
use IP\Support\PDF\PDFFactory;
use IP\Modules\CompanyProfiles\Models\CompanyProfile;


class UstvaController extends Controller {

    private $report;

    public function __construct(UstvaReport $report)
    {
        $this->report = $report;
    }

    public function index() {
        return view('ustva.options', ['companyProfiles' => ['' => trans('ip.all_company_profiles')] + CompanyProfile::getList()]);
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
