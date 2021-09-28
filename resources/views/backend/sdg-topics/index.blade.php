@extends('layout')
@section('title', 'SDG Topic')

@section('activeAddBtn', 'Yes')
@section('activeImportBtn', 'Yes')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">

            <table class="table table-striped w-100" id="laravel_datatable"> 
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>#</td>
                        <td class="w-25">Name</td>
                        <!-- <td>Company</td> -->
                        <th>
                            GRI Standards
                            <br/>
                            <small data-toggle="modal" data-target="#infoModal" class="text-primary">
                                <i class="fas fa-info-circle"></i>
                                Know GRI's definitions
                            </small>
                        </th>
                        <th>Added By</th>
                        <th>Created On</th>
                        <td>Action</td>
                    </tr>
                </thead>
            </table>

            <!-- modal -->
            @include('backend.sdg-topics.modal.gris')
            @include('backend.sdg-topics.modal.add_edit')
            @include('backend.sdg-topics.modal.import')
        </div>
    </div>

    <!-- datatable -->
    @include('backend.sdg-topics.datatable.script')

@endsection