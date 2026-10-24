@push('scripts')
<script>
    $(function () {
        $('#listTable').DataTable({
            pageLength: 25,
            order: [],
            scrollX: true,
            autoWidth: false,
            columnDefs: [{ orderable: false, targets: -1 }],
            language: { search: '_INPUT_', searchPlaceholder: 'Search…', lengthMenu: 'Show _MENU_ entries' }
        });
    });
</script>
@endpush
