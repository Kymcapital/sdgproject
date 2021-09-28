@extends('layout')
@section('title', 'Division')

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
                        <td>Name</td>
                        <!-- <td>Company</td> -->
                        <th>Added By</th>
                        <th>Created On</th>
                        <td>Action</td>
                    </tr>
                </thead>
            </table>

            <!-- modal -->
            @include('backend.divisions.modal.add_edit')
            @include('backend.divisions.modal.import')
        </div>
    </div>

    <!-- datatable -->
    @include('backend.divisions.datatable.script')

@endsection