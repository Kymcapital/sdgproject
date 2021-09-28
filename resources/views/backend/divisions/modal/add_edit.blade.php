<div class="modal fade" id="ajax-division-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="divisionCrudModal"></h6>
            </div>

            <div class="modal-body">

                <!-- ALERT MESSAGES -->
                @include('alert')
                
                <form id="divisionForm" name="divisionForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="division_id" id="division_id">

                    <div class="form-group">
                        <label for="label" class="control-label">Name</label>
                        <input type="text" class="form-control" id="label" name="label" placeholder="Enter Name" value="">
                        <div class="text-danger" id="labelError"></div>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="company_id" id="company_id" hidden>
                            
                            <!-- <option selected value="">Select Company</option> -->
                                
                            @foreach ($companies as $key => $company)
                                <option value="{{ $company->id }}" {{ ( $key == $company->id) ? 'selected' : '' }}> 
                                    {{ $company->name }}
                                </option>
                            @endforeach    
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save</button>
                </form>
            </div>

            <div class="modal-footer"></div>

        </div>
    </div>
</div>