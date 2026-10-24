<script src="{{ asset('dist-admin/js/jquery.min.js') }}"></script>
<script src="{{ asset('dist-admin/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist-admin/js/dataTables.min.js') }}"></script>
<script src="{{ asset('dist-admin/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('dist-admin/js/iziToast.min.js') }}"></script>
<script src="{{ asset('dist-admin/js/app.js') }}"></script>
<script src="{{ asset('dist-admin/js/custom.js') }}"></script>

@if($errors->any())
    <script>
        iziToast.error({ message: @json($errors->first()), position: 'topRight' });
    </script>
@endif

@if(session('success'))
    <script>
        iziToast.success({ message: @json(session('success')), position: 'topRight' });
    </script>
@endif

@if(session('error'))
    <script>
        iziToast.error({ message: @json(session('error')), position: 'topRight' });
    </script>
@endif
