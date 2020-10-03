@extends('reports.layouts.master')

@section('content')

    <style>
        .totals_label {
            font-weight:bold;
        }
    </style>

    <h1 style="margin-bottom: 0;">{{ trans('UstvaReport::common.title') }}</h1>
    <h3 style="margin-top: 0;">{{ $results['from_date'] }} - {{ $results['to_date'] }}</h3>

    <p>{{ trans('UstvaReport::report.introduction') }}</p>
    <ul>
        <li><b>35</b>: {{ $results['field_35'] }}</li>
        <li><b>36</b>: {{ $results['field_36'] }}</li>
        <li><b>81</b>: {{ $results['field_81'] }}</li>
        <li><b>86</b>: {{ $results['field_86'] }}</li>
        <li><b>66</b>: {{ $results['field_66'] }}</li>
        <li><b>83</b>: {{ $results['field_83'] }}</li>
    </ul>

@stop
