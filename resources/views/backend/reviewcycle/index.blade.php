@extends('layout')
@section('title', 'Review Cycle')

@section('activeAddBtn', 'Yes')
{{-- @section('activeImportBtn', 'no') --}}

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">

            <table class="table table-striped" id="cycletable">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>#</td>
                        <td>Review Cycle</td>
                        <td>Year</td>
                        <td>Start Date</td>
                        <td>End Date</td>
                        <td>Action</td>
                    </tr>
                </thead>
            </table>

            <!-- modal -->
            @include('backend.reviewcycle.modal.add_edit')
            @include('backend.reviewcycle.modal.import')

        </div>
    </div>

    <!-- datatable -->
    @include('backend.reviewcycle.datatable.script')

@endsection
