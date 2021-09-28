<script>
    var SITEURL = '{{URL::to('')}}';

    $(document).ready( function () {

        $('#label').change(function() {
           $('#year').val($('#label').val());
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        /*  LIST */
        $('#cycletable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "review-cycle", //SITEURL +
                type: 'GET',
            },
            columns: [
                {data: 'id', name: 'id', 'visible': false},
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'label', name: 'label' },
                {data: 'year', name: 'year' },
                {data: 'start_date', name: 'start_date', orderable: false},
                {data: 'end_date', name: 'end_date', orderable: false},
                {data: 'action', name: 'action', orderable: false},
            ],
            order: [[0, 'desc']],
        });

        /*  ADD (modal) */
        $('#create-new-review-cycle').click(function () {
            $('#btn-save').val("create-review-cycle");
            $('#cycleForm').trigger("reset");
            $('#cycleReviewTitle').html("Add New Review Cycle ");
            $('#cycleReviewModal').modal('show');
            $('#modal-preview').attr('src', 'https://via.placeholder.com/150');

            $('.alert').addClass('d-none');

            $('#labelError').text('');
            $('#start_dateError').text('');
            $('#end_dateError').text('');
        });

        /* EDIT */
        $('body').on('click', '.edit-review-cycle', function () {
            var review_cycle_id = $(this).data('id');
            $.get('review-cycle/' + review_cycle_id +'/edit', function (data) {

                $('#label-error').hide();
                $('#start_date-error').hide();
                $('#end_date-error').hide();

                $('#cycleReviewTitle').html("Edit Review Cycle");
                $('#btn-save').val("edit-review-cycle");
                $('#cycleReviewModal').modal('show');

                $('#label').val(data.label);
                $('#start_date').val(data.start_date);
                $('#year').val(data.year);
                $('#end_date').val(data.end_date);
                $('#review_cycle_id').val(data.id);

                $('.alert').addClass('d-none');

                $('#labelError').text('');
                $('#start_dateError').text('');
                $('#end_dateError').text('');
            })
        });

        /* DELETE */
        $('body').on('click', '#delete-review-cycle', function () {
            var review_cycle_id = $(this).data("id");

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
                        url: "review-cycle/"+review_cycle_id, //SITEURL +
                        success: function (data) {
                            var oTable = $('#cycletable').dataTable();
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
        $('body').on('submit', '#cycleForm', function (e) {
            e.preventDefault();
            var actionType = $('#btn-save').val();
            $('#btn-save').html('Sending..');
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: "review-cycle",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#cycleForm').trigger("reset");
                    $('#cycleReviewModal').modal('hide');
                    $('#btn-save').html('Save Changes');
                    var oTable = $('#cycletable').dataTable();
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
                        $('#start_dateError').text($.parseJSON(data.responseText).errors.start_date);
                        $('#end_dateError').text($.parseJSON(data.responseText).errors.end_date);
                    }else{
                        $('#labelError').text('');
                        $('#start_dateError').text('');
                        $('#end_dateError').text('');
                    }
                }
            });
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
