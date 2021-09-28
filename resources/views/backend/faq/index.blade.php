@extends('layout')
@section('title', 'FAQs')

@section('activeAddBtn', '')
@section('activeImportBtn', '')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">

        <div class="accordion" id="accordionExample">
            <div class="card">
                <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        What is my password or How do I reset/change password of the system. 
                    </button>
                </h2>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        The system does not store any passwords. 
                        <br/><br/>Once you have been registered as a user in the system, access the link and provide your email address then click on the provided button. 
                        <br/><br/>The system shall send you link containing a time limited token that validates your email therefore acting as an authentication verification procedure. 
                        <br/><br/>No password.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            What is the validity/duration of a KPI target.
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    <div class="card-body">
                        The target is applicable per review cycle.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        If i have entered wrong figure when providing review per KPI, how can that be corrected?
                    </button>
                </h2>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                    <div class="card-body">
                        When providing a feedback, your manager has to approve for the review to reflect. <br/><br/>
                        In this case, the approved shall reject the entry. <br/><br/>
                        You shall need to provide the correct entry to reflect which too shall need to be approved to reflect. 
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>

    <!-- datatable -->
    @include('backend.gri.datatable.script')

@endsection