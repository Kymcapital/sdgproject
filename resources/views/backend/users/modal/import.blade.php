<div class="modal fade" id="ajax-user-modal-import" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="userCrudModalImport"></h6>
            </div>

            <div class="modal-body">

                <!-- ALERT MESSAGES -->
                @include('alert')

                <form id="userFormImport" name="userFormImport" class="form-horizontal" enctype="multipart/form-data">

                    <div class="form-group">
                        <div class="row align-items-center">
                            <div class="col-sm-12">
                                Click <a href="/files/user/SDGUsers.xlsx" rel="noopener noreferrer" class="" download><i class="fas fa-file-download"></i> here</a> to download a sample file.
                                <hr class="mb-0">
                                <label class="control-label"></label>
                                <div class="">
                                    <input id="file" type="file" name="file" accept=".xlsx, .xls, .csv, .ods">
                                    <input type="hidden" name="hidden_file" id="hidden_file">
                                </div>
                                <div class="text-danger" id="fileError"></div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="btn-save-import" value="create">Save</button>
                </form>
            </div>

            <div class="modal-footer"></div>

        </div>
    </div>
</div>