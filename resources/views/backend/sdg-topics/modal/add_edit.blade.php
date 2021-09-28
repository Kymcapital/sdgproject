<div class="modal fade" id="ajax-sdg-topic-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="sdg-topicCrudModal"></h6>
            </div>

            <div class="modal-body">

                <!-- ALERT MESSAGES -->
                @include('alert')
                
                <form id="sdg-topicForm" name="sdg-topicForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="sdg_topic_id" id="sdg_topic_id">

                    <div class="form-group">
                        <label for="label" class="control-label">Name</label>
                        <input type="text" class="form-control" id="label" name="label" placeholder="Enter Name" value="">
                        <div class="text-danger" id="labelError"></div>
                    </div>

                    <div class="form-group">

                        <select class="form-control" name="gri_id[]" id="gri_id" size="8" multiple aria-label="multiple select GRI Standards">
                                    
                            <option selected value="">Select GRI Standards</option>
                                
                            @foreach ($gris as $key => $gri)
                                <option value="{{ $gri->id }}" {{ ( $key == $gri->id) ? 'selected' : '' }}> 
                                    {{ $gri->gri_number }}
                                </option>
                            @endforeach    
                        </select>


                        <!-- <div class="control-label">Select GRI Standards</div>
                        <hr class="mt-2"/>
                        
                        @foreach ($gris as $key => $gri)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="gri_id{{$gri->id}}" name="gri_id[]" value="{{$gri->id}}">
                                <label class="form-check-label" for="gri_id{{$gri->id}}">{{$gri->gri_number}}</label>
                            </div>
                        @endforeach   -->
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