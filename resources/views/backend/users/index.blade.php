@extends('layout')
@section('title', 'User')

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
                        <td>Division</td>
                        <td>Email-Address</td>
                        <td>Role</td>
                        <td>Permissions</td>
                        <th>Added By</th>
                        <!-- <td>Company</td>  -->
                        <th>Created at</th>
                        <td>Action</td>
                    </tr>
                </thead>
            </table>

            <!-- modal -->
            @include('backend.users.modal.add_edit')
            @include('backend.users.modal.import')
        </div>
    </div>

    <!-- datatable -->
    @include('backend.users.datatable.script')

@endsection