<script>
    var SITEURL = '{{URL::to('')}}';

    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        /*  LIST */
        $('#laravel_datatable_gri').DataTable({
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
            ],
            order: [[3, 'asc']],
        });

    });
</script>