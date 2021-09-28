@extends('layout')
@section('title', 'KPI')

@section('activeAddBtn', 'Yes')
{{-- @section('activeImportBtn', 'Yes') --}}

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">

            <table class="table table-striped" id="laravel_datatable">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>#</td>
                        <td>SDG Metric / KPI </td>
                        <td>Review Cycle</td>
                        <td>Target</td>
                        <td>SDG Topic</td>
                        <!--
                        <td>Division</td>-->
                        <th>Added By</th>
                        <th>Created on</th>
                        <td>Action</td>
                    </tr>
                </thead>
            </table>

            <!-- modal -->
            @include('backend.kpis.modal.add_edit')
            @include('backend.kpis.modal.import')
        </div>
    </div>

    <!-- datatable -->
    @include('backend.kpis.datatable.script')

@endsection
