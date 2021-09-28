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
                url: "kpis", //SITEURL +
                type: 'GET',
            },
            columns: [
                {data: 'id', name: 'id', 'visible': false},
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'label', name: 'label' },
                {data: 'cycle_id[].label', name: 'cycle_id' },
                {data: 'target', name: 'target' },
                {data: 'sdg_topic_id', name: 'sdg_topic_id' },
                // {data: 'division_id', name: 'division_id' },
                {data: 'user_id', name: 'user_id' },
                {data: 'created_at.formated_date', name: 'created_at', orderable: true},
                {data: 'action', name: 'action', orderable: false},
            ],
            order: [[0, 'desc']],
        });

        /*  ADD (modal) */
        $('#create-new-kpi').click(function () {
            $('#btn-save').val("create-kpi");
            $('#kpi_id').val('');
            $('#kpiForm').trigger("reset");
            $('#kpiCrudModal').html("Add New KPI");
            $('#ajax-kpi-modal').modal('show');
            $('#modal-preview').attr('src', 'https://via.placeholder.com/150');

            $('.alert').addClass('d-none');

            $('#labelError').text('');
            $('#cycleError').text('');
            $('#targetError').text('');
        });

        /* EDIT */
        $('body').on('click', '.edit-kpi', function () {
            var kpi_id = $(this).data('id');
            $.get('kpis/' + kpi_id +'/edit', function (data) {

                $('#label-error').hide();
                $('#cycle_id-error').hide();
                $('#target-error').hide();

                $('#kpiCrudModal').html("Edit KPI");
                $('#btn-save').val("edit-kpi");
                $('#ajax-kpi-modal').modal('show');

                $('#label').val(data.label);
                $('#cycle_id').val(data.cycle_id);
                $('#target').val(data.target);
                $('#company_id').val(data.company_id);
                $('#sdg_topic_id').val(data.sdg_topic_id);
                $('#division_id').val(data.division_id);
                $('#kpi_id').val(data.id);

                $('#modal-preview').attr('alt', 'No logo available');
                if(data.logo){
                    $('#modal-preview').attr('src', '/images/kpi/'+data.logo); //SITEURL +
                    $('#hidden_logo').attr('src', '/images/kpi/'+data.logo); //SITEURL +
                }

                $('.alert').addClass('d-none');

                $('#labelError').text('');
                $('#cycleError').text('');
                $('#targetError').text('');
            })
        });

        /* DELETE */
        $('body').on('click', '#delete-kpi', function () {
            var kpi_id = $(this).data("id");

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
                        url: "kpis/"+kpi_id, //SITEURL +
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
        $('body').on('submit', '#kpiForm', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save').val();
            $('#btn-save').html('Sending..');
            var formData = new FormData(this);
            console.log(formData);
            $.ajax({
                type:'POST',
                url: "kpis",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    console.log(data);
                    $('#kpiForm').trigger("reset");
                    $('#ajax-kpi-modal').modal('hide');
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
                        $('#labelError').text($.parseJSON(data.responseText).errors.label);
                        $('#cycleError').text($.parseJSON(data.responseText).errors.cycle_id);
                        $('#targetError').text($.parseJSON(data.responseText).errors.target);
                    }else{
                        $('#labelError').text('');
                        $('#cycleError').text('');
                        $('#targetError').text('');
                    }
                }
            });
        });

        /* IMPORT (modal) */
        $('#import-new-kpis').click(function () {
            $('#btn-save-import').val("import-kpi");
            $('#kpiFormImport').trigger("reset");
            $('#kpiCrudModalImport').html("Import KPI's");
            $('#ajax-kpi-modal-import').modal('show');
            $('.alert').addClass('d-none');

            $('#fileError').text('');
        });

        /* SUBMIT FORM (import)*/
        $('body').on('submit', '#kpiFormImport', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save-import').val();
            $('#btn-save-import').html('Importing..');
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: "kpis/import/store",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#kpiFormImport').trigger("reset");
                    $('#ajax-kpi-modal-import').modal('hide');
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
