<script>
    var SITEURL = '{{URL::to('')}}';

    //console.log(SITEURL);

    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        /*  LIST */
        $('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "divisions", //SITEURL +
                type: 'GET',
            },
            columns: [
                {data: 'id', name: 'id', 'visible': false},
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'label', name: 'label' },
                // {data: 'company_id', name: 'company_id' },
                {data: 'user_id', name: 'user_id' },
                {data: 'created_at.formated_date', name: 'created_at', orderable: false},
                {data: 'action', name: 'action', orderable: false},
            ],
            order: [[3, 'asc']],
        });

        /*  ADD (modal) */
        $('#create-new-division').click(function () {
            $('#btn-save').val("create-division");
            $('#division_id').val('');
            $('#divisionForm').trigger("reset");
            $('#divisionCrudModal').html("Add New Division");
            $('#ajax-division-modal').modal('show');
            $('#modal-preview').attr('src', 'https://via.placeholder.com/150');

            $('.alert').addClass('d-none');

            $('#labelError').text('');
        });

        /* EDIT */
        $('body').on('click', '.edit-division', function () {
            var division_id = $(this).data('id');
            $.get('divisions/' + division_id +'/edit', function (data) {

                $('#label-error').hide();

                $('#divisionCrudModal').html("Edit Division");
                $('#btn-save').val("edit-division");
                $('#ajax-division-modal').modal('show');

                $('#division_id').val(data.id);
                $('#label').val(data.label);
                $('#company_id').val(data.company_id);

                $('.alert').addClass('d-none');

                $('#labelError').text('');
            })
        });

        /* DELETE */
        $('body').on('click', '#delete-division', function () {
            var division_id = $(this).data("id");

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this account!",
                type: "error",
                showCancelButton: true,
                dangerMode: true,
                cancelButtonClass: '#DD6B55',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Delete!',
            },function (result) {
                if (result) {
                    $.ajax({
                        type: "delete",
                        url: "divisions/"+division_id, //SITEURL+
                        success: function (data) {
                            var oTable = $('#laravel_datatable').dataTable(); 
                                oTable.fnDraw(false);

                            if (data.message) {
                                $('.alert').text(data.message);
                                $('.alert').removeClass('d-none');
                                if(data.status = 'success'){
                                    $('.alert').addClass('alert-success');
                                    $('.alert').removeClass('alert-danger');
                                }
                            }
                        },
                        error: function (data) {
                            if($.parseJSON(data.responseText).message){
                                $('.alert').text($.parseJSON(data.responseText).message);
                                $('.alert').removeClass('d-none');
                                if($.parseJSON(data.responseText).status = 'error'){
                                    $('.alert').addClass('alert-danger');
                                    $('.alert').removeClass('alert-success');
                                }
                            }
                        }
                    });
                }
            });

            $('.alert').addClass('d-none');
        });

        /* SUBMIT FORM (add)*/
        $('body').on('submit', '#divisionForm', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save').val();
            $('#btn-save').html('Sending..');
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: "divisions",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#divisionForm').trigger("reset");
                    $('#ajax-division-modal').modal('hide');
                    $('#btn-save').html('Save Changes');
                    var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);

                    if (data.message) {
                        $('.alert').text(data.message);
                        $('.alert').removeClass('d-none');
                        if(data.status = 'success'){
                            $('.alert').addClass('alert-success');
                            $('.alert').removeClass('alert-danger');
                            $('.isModal').removeClass('d-none');
                        }
                    }

                },
                error: function(data){
                    $('#btn-save').html('Save Changes');

                    //alert errors
                    if($.parseJSON(data.responseText).message){
                        $('.alert').text($.parseJSON(data.responseText).message);
                        $('.alert').removeClass('d-none');
                        if($.parseJSON(data.responseText).status = 'error'){
                            $('.alert').addClass('alert-danger');
                            $('.alert').removeClass('alert-success');
                            $('.isModal').removeClass('d-none');
                        }
                    }
                    //form errors
                    if($.parseJSON(data.responseText).errors){
                        $('#labelError').text($.parseJSON(data.responseText).errors.label[0]);
                    }else{
                        $('#labelError').text('');
                    }

                    //console.log('Error:', data);
                }
            });
        });

        /* IMPORT (modal) */
        $('#import-new-divisions').click(function () {
            $('#btn-save-import').val("import-division");
            $('#divisionFormImport').trigger("reset");
            $('#divisionCrudModalImport').html("Import Divisions");
            $('#ajax-division-modal-import').modal('show');
            $('.alert').addClass('d-none');

            $('#fileError').text('');
        });

        /* SUBMIT FORM (import)*/
        $('body').on('submit', '#divisionFormImport', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save-import').val();
            $('#btn-save-import').html('Importing..');
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: "divisions/import/store",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#divisionFormImport').trigger("reset");
                    $('#ajax-division-modal-import').modal('hide');
                    $('#btn-save-import').html('Save Changes');
                    var oTable = $('#laravel_datatable').dataTable();
                        oTable.fnDraw(false);

                    if (data.message) {
                        $('.alert').text(data.message);
                        $('.alert').removeClass('d-none');
                        if(data.status = 'success'){
                            $('.alert').addClass('alert-success');
                            $('.alert').removeClass('alert-danger');
                            $('.isModal').removeClass('d-none');
                        }
                    }

                },
                error: function(data){

                    //alert errors
                    if($.parseJSON(data.responseText).message){
                        $('.alert').text($.parseJSON(data.responseText).message);
                        $('.alert').removeClass('d-none');
                        if($.parseJSON(data.responseText).status = 'error'){
                            $('.alert').addClass('alert-danger');
                            $('.alert').removeClass('alert-success');
                            $('.isModal').removeClass('d-none');
                        }
                    }

                    //form errors
                    if($.parseJSON(data.responseText).errors){
                        $('#fileError').text($.parseJSON(data.responseText).errors.file[0]);
                    }else{
                        $('#fileError').text('');
                    }

                    console.log('Error:', data);
                    $('#btn-save-import').html('Save Changes');
                }
            });
        });

    });
</script>