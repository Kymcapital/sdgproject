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
                url: "sdg-topics", //SITEURL +
                type: 'GET',
            },
            columns: [
                {data: 'id', name: 'id', 'visible': false},
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'label', name: 'label' },
                {data: 'gri_id[].gri_number', name: 'gri_id', orderable: false, searchable: false },
                // { 
                //     "name": "gri_id",
                //     data: 'gri_id[].gri_number',
                //     "render": function(data, type, row, meta){
                //         if(type === 'display'){
                //             data.forEach(function(entry) {
                //                 data = '<a href="' + entry + '">' + entry + '</a>';
                //             });
                //         }
                //         return data;
                //     }
                // },
                // {data: 'company_id', name: 'company_id' },
                {data: 'user_id', name: 'user_id' },
                {data: 'created_at.formated_date', name: 'created_at', orderable: false},
                {data: 'action', name: 'action', orderable: false},
            ],
            order: [[3, 'asc']],
        });

        /*  ADD (modal) */
        $('#create-new-sdg-topic').click(function () {
            $('#btn-save').val("create-sdg-topic");
            $('#sdg_topic_id').val('');
            $('#sdg-topicForm').trigger("reset");
            $('#sdg-topicCrudModal').html("Add New SDG Topic");
            $('#ajax-sdg-topic-modal').modal('show');
            $('#modal-preview').attr('src', 'https://via.placeholder.com/150');

            $('.alert').addClass('d-none');

            $('#labelError').text('');
        });

        /* EDIT */
        $('body').on('click', '.edit-sdg-topic', function () {
            var sdg_topic_id = $(this).data('id');
            $.get('sdg-topics/' + sdg_topic_id +'/edit', function (data) {

                $('#label-error').hide();

                $('#sdg-topicCrudModal').html("Edit SDG");
                $('#btn-save').val("edit-sdg-topic");
                $('#ajax-sdg-topic-modal').modal('show');

                $('#sdg_topic_id').val(data.id);
                $('#label').val(data.label);
                //var dd = data.gri_id;
                // $('#gri_id'+data.gri_id).each(function()
                // { 
                //     //$checkedBoxes.push($(this).val(data.gri_id));
                //     $('#gri_id'+data.gri_id ).val(data.gri_id);
                // });
                $('#gri_id').val(data.gri_id);
                $('#company_id').val(data.company_id);

                $('.alert').addClass('d-none');

                $('#labelError').text('');
            })
        });

        /* DELETE */
        $('body').on('click', '#delete-sdg-topic', function () {
            var sdg_topic_id = $(this).data("id");

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
                        url: "sdg-topics/"+sdg_topic_id, //SITEURL +
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
        $('body').on('click', '#status-sdg-topic', function () {
            var sdg_topic_id = $(this).data("id");
            
            $.get('sdg-topics/' + sdg_topic_id +'/edit', function (data) {

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
                                url: "sdg-topics/status/"+sdg_topic_id+'/'+status, //SITEURL +
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
                                url: "sdg-topics/status/"+sdg_topic_id+'/'+status, //SITEURL +
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
        $('body').on('submit', '#sdg-topicForm', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save').val();
            $('#btn-save').html('Sending..');
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: "sdg-topics",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#sdg-topicForm').trigger("reset");
                    $('#ajax-sdg-topic-modal').modal('hide');
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
                }
            });
        });

        /* IMPORT (modal) */
        $('#import-new-sdg-topics').click(function () {
            $('#btn-save-import').val("import-sdg-topic");
            $('#sdg-topicFormImport').trigger("reset");
            $('#sdg-topicCrudModalImport').html("Import SDG Topics");
            $('#ajax-sdg-topic-modal-import').modal('show');
            $('.alert').addClass('d-none');

            $('#fileError').text('');
        });

        /* SUBMIT FORM (import)*/
        $('body').on('submit', '#sdg-topicFormImport', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save-import').val();
            $('#btn-save-import').html('Importing..');
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: "sdg-topics/import/store",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#sdg-topicFormImport').trigger("reset");
                    $('#ajax-sdg-topic-modal-import').modal('hide');
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