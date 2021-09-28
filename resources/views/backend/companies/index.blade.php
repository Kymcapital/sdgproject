@extends('layout')
@section('title', 'Company')

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
                        <td>Logo</td>
                        <td>Name</td>
                        <td>Contact Email</td>
                        <th>Created at</th>
                        <th>Status</th>
                        <td>Action</td>
                    </tr>
                </thead>
            </table>

            <!-- modal -->
            @include('backend.companies.modal.add_edit')
            @include('backend.companies.modal.import')
        </div>
    </div>

    <!-- datatable -->
    @include('backend.companies.datatable.script')

@endsection