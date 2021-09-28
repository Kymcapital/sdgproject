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
                url: "companies", //SITEURL +
                type: 'GET',
            },
            columns: [
                {data: 'id', name: 'id', 'visible': false},
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'logo', name: 'logo', orderable: false},
                {data: 'name', name: 'name' },
                {data: 'contact_email', name: 'contact_email' },
                {data: 'created_at.formated_date', name: 'created_at', orderable: false},
                {data: 'status', name: 'status', orderable: false},
                {data: 'action', name: 'action', orderable: false},
            ],
            order: [[0, 'desc']],
        });

        /*  ADD (modal) */
        $('#create-new-company').click(function () {
            $('#btn-save').val("create-company");
            $('#company_id').val('');
            $('#companyForm').trigger("reset");
            $('#companyCrudModal').html("Add New Company");
            $('#ajax-company-modal').modal('show');
            $('#modal-preview').attr('src', 'https://via.placeholder.com/150');

            $('.alert').addClass('d-none');

            $('#nameError').text('');
            $('#contactEmailError').text('');
        });

        /* EDIT */
        $('body').on('click', '.edit-company', function () {
            var company_id = $(this).data('id');
            $.get('companies/' + company_id +'/edit', function (data) {

                $('#name-error').hide();
                $('#contact_email-error').hide();

                $('#companyCrudModal').html("Edit Company");
                $('#btn-save').val("edit-company");
                $('#ajax-company-modal').modal('show');

                $('#company_id').val(data.id);
                $('#name').val(data.name);
                $('#contact_email').val(data.contact_email);
                
                $('#modal-preview').attr('alt', 'No logo available');
                if(data.logo){
                    $('#modal-preview').attr('src', '/images/company/'+data.logo); //SITEURL +
                    $('#hidden_logo').attr('src', '/images/company/'+data.logo); //SITEURL +
                }

                $('.alert').addClass('d-none');

                $('#nameError').text('');
                $('#contactEmailError').text('');
            })
        });

        /* DELETE */
        $('body').on('click', '#delete-company', function () {
            var company_id = $(this).data("id");

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
                        url: "companies/"+company_id, //SITEURL +
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
        $('body').on('click', '#status-company', function () {
            var company_id = $(this).data("id");
            
            $.get('companies/' + company_id +'/edit', function (data) {

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
                                url: "companies/status/"+company_id+'/'+status, //SITEURL +
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
                                url: "companies/status/"+company_id+'/'+status, //SITEURL +
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
        $('body').on('submit', '#companyForm', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save').val();
            $('#btn-save').html('Sending..');
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: "companies",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#companyForm').trigger("reset");
                    $('#ajax-company-modal').modal('hide');
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
                        $('#nameError').text($.parseJSON(data.responseText).errors.name[0]);
                        $('#contactEmailError').text($.parseJSON(data.responseText).errors.contact_email[0]);
                        //$('#logoError').text($.parseJSON(data.responseText).errors.logo[0]);
                    }else{
                        $('#nameError').text('');
                        $('#contactEmailError').text('');
                        //$('#logoError').text('');
                    }

                    //console.log('Error:', data);
                }
            });
        });

        /* IMPORT (modal) */
        $('#import-new-companies').click(function () {
            $('#btn-save-import').val("import-company");
            $('#companyFormImport').trigger("reset");
            $('#companyCrudModalImport').html("Import Companies");
            $('#ajax-company-modal-import').modal('show');
            $('.alert').addClass('d-none');

            $('#fileError').text('');
        });

        /* SUBMIT FORM (import)*/
        $('body').on('submit', '#companyFormImport', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save-import').val();
            $('#btn-save-import').html('Importing..');
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: "companies/import/store",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#companyFormImport').trigger("reset");
                    $('#ajax-company-modal-import').modal('hide');
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