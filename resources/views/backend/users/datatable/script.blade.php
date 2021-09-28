<script>
    //var SITEURL = '{{URL::to('')}}';
    //var SITEURL = '{{env('APP_URL')}}';
    //var SITEURL = {!! json_encode(url('/')) !!};
    //var SITEURL = "{{ url('/') }}";

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
                url: "users", //SITEURL+
                type: 'GET',
            },
            columns: [
                {data: 'id', name: 'id', 'visible': false},
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'first_name', searchable: false},
                {data: 'division_id', name: 'division_id', searchable: true },
                {data: 'email', name: 'email', searchable: true },
                {data: 'role_id', name: 'role_id', searchable: true },
                {data: 'permission_id', name: 'permission_id' },
                {data: 'user_id', name: 'user_id' },
                // {data: 'company_id', name: 'company_id' },
                {data: 'created_at.formated_date', name: 'created_at', orderable: false},
                {data: 'action', name: 'action', orderable: false},
            ],
            order: [[0, 'desc']],
        });

        /*  ADD (modal) */
        $('#create-new-user').click(function () {
            $('#btn-save').val("create-user");
            $('#user_id').val('');
            $('#userForm').trigger("reset");
            $('#userCrudModal').html("Add New User");
            $('#ajax-user-modal').modal('show');
            $('#modal-preview').attr('src', 'https://via.placeholder.com/150');

            $('.alert').addClass('d-none');

            $('#firstNameError').text('');
            $('#lastNameError').text('');
            $('#emailError').text('');
        });

        /* EDIT */
        $('body').on('click', '.edit-user', function () {
            var user_id = $(this).data('id');
            $.get('users/' + user_id +'/edit', function (data) {

                $('#firstNameError').hide();
                $('#lastNameError').hide();
                $('#emailError').hide();

                $('#userCrudModal').html("Edit User");
                $('#btn-save').val("edit-user");
                $('#ajax-user-modal').modal('show');

                $('#user_id').val(data.id);
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                $('#email').val(data.email);
                $('#company_id').val(data.company_id);
                $('#division_id').val(data.division_id);
                $('#role_id').val(data.role_id);
                $('#permission_id').val(data.permission_id);
                
                $('#modal-preview').attr('alt', 'No logo available');
                if(data.logo){
                    $('#modal-preview').attr('src', '/images/user/'+data.logo); //SITEURL+
                    $('#hidden_logo').attr('src', '/images/user/'+data.logo); //SITEURL+
                }

                $('.alert').addClass('d-none');

                $('#firstNameError').text('');
                $('#lastNameError').text('');
                $('#emailError').text('');
            })
        });

        /* DELETE */
        $('body').on('click', '#delete-user', function () {
            var user_id = $(this).data("id");

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
                        url: "users/"+user_id, //SITEURL+
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
        $('body').on('submit', '#userForm', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save').val();
            $('#btn-save').html('Sending..');
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: "users", //SITEURL+
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#userForm').trigger("reset");
                    $('#ajax-user-modal').modal('hide');
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
                        $('#firstNameError').text($.parseJSON(data.responseText).errors.first_name);
                        $('#lastNameError').text($.parseJSON(data.responseText).errors.last_name);
                        $('#emailError').text($.parseJSON(data.responseText).errors.email);
                    }else{
                        $('#firstNameError').text('');
                        $('#lastNameError').text('');
                        $('#emailError').text('');
                    }
                }
            });
        });

        /* IMPORT (modal) */
        $('#import-new-users').click(function () {
            $('#btn-save-import').val("import-user");
            $('#userFormImport').trigger("reset");
            $('#userCrudModalImport').html("Import Users");
            $('#ajax-user-modal-import').modal('show');
            $('.alert').addClass('d-none');

            $('#fileError').text('');
        });

        /* SUBMIT FORM (import)*/
        $('body').on('submit', '#userFormImport', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save-import').val();
            $('#btn-save-import').html('Importing..');
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: "users/import/store",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#userFormImport').trigger("reset");
                    $('#ajax-user-modal-import').modal('hide');
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