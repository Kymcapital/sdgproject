@extends('layout')
@section('title', 'GRI')

@section('activeAddBtn', 'Yes')
@section('activeImportBtn', 'Yes')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">

            <table class="table table-striped" id="laravel_datatable"> 
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>#</td>
                        <td>GRI</td>
                        <th class="w-25">Description</th>
                        <th>Added By</th>
                        <!-- <th>Company</th> -->
                        <th>Created On</th>
                        <td>Action</td>
                    </tr>
                </thead>
            </table>

            <!-- modal -->
            @include('backend.gri.modal.add_edit')
            @include('backend.gri.modal.import')
        </div>
    </div>

    <!-- datatable -->
    @include('backend.gri.datatable.script')

@endsection