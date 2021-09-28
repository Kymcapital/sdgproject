<script>
    var SITEURL = '{{URL::to('')}}';

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
                url: "gris", //SITEURL +
                type: 'GET',
            },
            columns: [
                {data: 'id', name: 'id', 'visible': false},
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'gri_number', name: 'gri_number' },
                {data: 'description', name: 'description' },
                // {data: 'company_id', name: 'company_id' },
                {data: 'user_id', name: 'user_id' },
                {data: 'created_at.formated_date', name: 'created_at', orderable: false},
                {data: 'action', name: 'action', orderable: false},
            ],
            order: [[3, 'asc']],
        });

        /*  ADD (modal) */
        $('#create-new-gri').click(function () {
            $('#btn-save').val("create-gri");
            $('#gri_id').val('');
            $('#griForm').trigger("reset");
            $('#griCrudModal').html("Add New GRI");
            $('#ajax-gri-modal').modal('show');
            $('#modal-preview').attr('src', 'https://via.placeholder.com/150');

            $('.alert').addClass('d-none');

            $('#gri_numberError').text('');
        });

        /* EDIT */
        $('body').on('click', '.edit-gri', function () {
            var gri_id = $(this).data('id');
            $.get('gris/' + gri_id +'/edit', function (data) {

                $('#gri_number-error').hide();

                $('#griCrudModal').html("Edit GRI");
                $('#btn-save').val("edit-gri");
                $('#ajax-gri-modal').modal('show');

                $('#gri_id').val(data.id);
                $('#gri_number').val(data.gri_number);
                $('#description').val(data.description);
                $('#company_id').val(data.company_id);

                $('.alert').addClass('d-none');

                $('#gri_numberError').text('');
            })
        });

        /* DELETE */
        $('body').on('click', '#delete-gri', function () {
            var gri_id = $(this).data("id");

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
                        url: "gris/"+gri_id, //SITEURL +
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

        /* STATUS (lock - unlock) */
        $('body').on('click', '#status-gri', function () {
            var gri_id = $(this).data("id");
            
            $.get('gris/' + gri_id +'/edit', function (data) {

                if(data.status){
                    var statusVal = "Lock";
                }else{
                    var statusVal = "Un-lock";
                }

                swal({
                    title:"",
                    text: "Are you sure you want to " +statusVal+ " this account?",
                    type: "warning",
                    showCancelButton: true,
                    dangerMode: true,
                    cancelButtonClass: '#DD6B55',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: statusVal+'!',
                },function (result) {
                    if (result) {

                        if(data.status){
                            var status = 0;
                            $.ajax({
                                type: "post",
                                url: "gris/status/"+gri_id+'/'+status, //SITEURL +
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
                        }else{
                            var status = 1;
                            $.ajax({
                                type: "post",
                                url: "gris/status/"+gri_id+'/'+status, //SITEURL +
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
                    }
                });

                $('.alert').addClass('d-none');
            })
        });

        /* SUBMIT FORM (add)*/
        $('body').on('submit', '#griForm', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save').val();
            $('#btn-save').html('Sending..');
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: "gris",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#griForm').trigger("reset");
                    $('#ajax-gri-modal').modal('hide');
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
                        $('#gri_numberError').text($.parseJSON(data.responseText).errors.gri_number[0]);
                    }else{
                        $('#gri_numberError').text('');
                    }
                }
            });
        });

        /* IMPORT (modal) */
        $('#import-new-gris').click(function () {
            $('#btn-save-import').val("import-gri");
            $('#griFormImport').trigger("reset");
            $('#griCrudModalImport').html("Import GRI");
            $('#ajax-gri-modal-import').modal('show');
            $('.alert').addClass('d-none');

            $('#fileError').text('');
        });

        /* SUBMIT FORM (import)*/
        $('body').on('submit', '#griFormImport', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save-import').val();
            $('#btn-save-import').html('Importing..');
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: "gris/import/store",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#griFormImport').trigger("reset");
                    $('#ajax-gri-modal-import').modal('hide');
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