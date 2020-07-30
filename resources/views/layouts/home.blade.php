{{-- Extends --}}

@extends('layouts.master')

{{-- end Extends --}}



{{-- Section --}}

@section('content')
<h4 class="text-info mb-3">Dashboard <i class="fa fa-dashboard"></i></h4>

@include('layouts.StatCountData')
@endsection

@section('linkCss')

@endsection

@section('script')

@endsection

@section('title','Home')

{{-- End Section --}}
