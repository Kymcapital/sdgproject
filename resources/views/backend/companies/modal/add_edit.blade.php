<div class="modal fade" id="ajax-company-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="companyCrudModal"></h6>
            </div>

            <div class="modal-body">

                <!-- ALERT MESSAGES -->
                @include('alert')
                
                <form id="companyForm" name="companyForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="company_id" id="company_id">

                    <div class="form-group">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="">
                        <div class="text-danger" id="nameError"></div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="control-label">Email Address</label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="Enter Email Address" value="">
                        <div class="text-danger" id="contactEmailError"></div>
                    </div>

                    <div class="form-group">
                        <div class="row align-items-center">
                            <div class="col-sm-3">
                                <img id="modal-preview" src="https://via.placeholder.com/150" alt="Preview" class="img-fluid hidden" height="100">
                            </div>
                            <div class="col-sm-9">
                                <label class="control-label">Logo</label>
                                <div class="">
                                    <input id="logo" type="file" name="logo" max-file-size="1024" onchange="readURL(this);">
                                    <input type="hidden" name="hidden" id="hidden">
                                    <div class="text-danger" id="logoError"></div>
                                </div>
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