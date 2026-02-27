<script src="{{ asset('cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js') }}"
    crossorigin="anonymous"></script>
<script src="{{ asset('asset/js/scripts.js') }}"></script>
<script src="{{ asset('js/latest.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('asset/js/datatables/datatables-simple-demo.js') }}"></script>
<script src="{{ asset('cdn.jsdelivr.net/npm/litepicker/dist/bundle.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('asset/js/litepicker.js') }}"></script>
<script src="{{ asset('js/jquery-3.5.1.js') }}"></script>
<script src="{{ asset('js/jqueryDataTable.min.js') }}"></script>
<script src="{{ asset('js/jqueryDataTableButtons.min.js') }}"></script>
<script src="{{ asset('js/jszip.min.js') }}"></script>
<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/vfs_fonts.min.js') }}"></script>
<script src="{{ asset('js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/buttons.print.min.js') }}"></script>
<script src="{{ asset('js/buttons.colvis.min.js') }}"></script>

<script type="text/javascript">
    $('#datatablesSimple1').DataTable({
        dom: 'Bfrtip',
        buttons: [

            {
                extend: 'colvis',
                text: 'Visibilité des colonnes',

            },
            {
                extend: 'print',
                text: 'Imprimer',
                exportOptions: {
                    columns: ':visible:not(:last-child)'
                }
            },

            {
                extend: 'pdfHtml5',
                text: ' PDF',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':visible:not(:last-child)'
                }
            },

            {
                extend: 'excelHtml5',
                text: 'EXCEL<br>',
                exportOptions: {
                    columns: ':visible:not(:last-child)'
                }
            },
        ]


    });
</script>
<script src="{{ asset('assets.startbootstrap.com/js/sb-customizer.js') }}"></script>
