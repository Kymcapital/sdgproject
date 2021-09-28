<div class="modal fade" id="ajax-user-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="userCrudModal"></h6>
            </div>

            <div class="modal-body">

                <!-- ALERT MESSAGES -->
                @include('alert')
                
                <form id="userForm" name="userForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" id="user_id">

                    <div class="row form-group">
                        <div class="col">
                            <label for="first_name" class="control-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" value="">
                            <div class="text-danger" id="firstNameError"></div>
                        </div>
                        <div class="col">
                            <label for="last_name" class="control-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" value="">
                            <div class="text-danger" id="lastNameError"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter E-mail Address" value="">
                        <div class="text-danger" id="emailError"></div>
                    </div>

                    <div class="row form-group">
                        <div class="col">
                            <div class="form-group">
                                <label for="division_id" class="control-label">Division</label>
                                <select class="form-control" name="division_id" id="division_id">
                                    
                                    <option selected value="">Select Division</option>
                                        
                                    @foreach ($divisions as $key => $division)
                                        <option value="{{ $division->id }}" {{ ( $key == $division->id) ? 'selected' : '' }}> 
                                            {{ $division->label }}
                                        </option>
                                    @endforeach    
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="role_id" class="control-label">Role</label>
                                <select class="form-control" name="role_id" id="role_id">
                                    
                                    <option selected value="">Select Role</option>
                                        
                                    @foreach ($roles as $key => $role)
                                        <option value="{{ $role->id }}" {{ ( $key == $role->id) ? 'selected' : '' }}> 
                                            {{ $role->name }}
                                        </option>
                                    @endforeach    
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col">
                            <div class="form-group">
                                <label for="role" class="control-label">Permission</label>
                                <select class="form-control" name="permission_id" id="permission_id">
                                    
                                    <option selected value="">Select Permission</option>
                                        
                                    @foreach ($permissions as $key => $permission)
                                        <!-- @if($key < 0)
                                            <option value="{{$key}}">Non</option>
                                        @endif -->
                                        <option value="{{ $permission->id }}" {{ ( $key == $permission->id) ? 'selected' : '' }}> 
                                            {{ $permission->name }}
                                        </option>
                                    @endforeach    
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="company_id" class="control-label" hidden>Company</label>
                                <select class="form-control" name="company_id" id="company_id" hidden>
                                    
                                    <!-- <option selected value="">Select Company</option> -->
                                        
                                    @foreach ($companies as $key => $company)
                                        <option value="{{ $company->id }}" {{ ( $key == $company->id) ? 'selected' : '' }}> 
                                            {{ $company->name }}
                                        </option>
                                    @endforeach    
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save</button>
                </form>
            </div>

            <div class="modal-footer"></div>

        </div>
    </div>
</div>