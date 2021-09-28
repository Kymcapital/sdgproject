<div class="modal fade" id="ajax-gri-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="griCrudModal"></h6>
            </div>

            <div class="modal-body">

                <!-- ALERT MESSAGES -->
                @include('alert')
                
                <form id="griForm" name="griForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="gri_id" id="gri_id">

                    <div class="form-group">
                        <label for="gri_number" class="control-gri_number">GRI Number</label>
                        <input type="number" class="form-control" id="gri_number" name="gri_number" placeholder="Enter Number" min="0" value="">
                        <div class="text-danger" id="gri_numberError"></div>
                    </div>

                    <div class="form-group">
                        <textarea name="description" class="form-control" id="description" cols="30" rows="10"></textarea>
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