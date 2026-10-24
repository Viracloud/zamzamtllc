<script>
$(function () {
    // Live preview for icon inputs
    $(document).on('input', '.icon-input', function () {
        var name = this.value.trim();
        $(this).closest('.icon-field').find('.icon-preview i').attr('class', 'fa-solid ' + (/^fa-[a-z0-9-]+$/.test(name) ? name : 'fa-icons'));
    });

    // Rich text editors
    if ($('.html-editor').length) {
        tinymce.init({
            selector: '.html-editor',
            height: 420,
            menubar: false,
            plugins: 'lists link code table wordcount',
            toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link table | code | removeformat',
            block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4',
            content_style: 'body { font-family: system-ui, -apple-system, sans-serif; font-size: 15px; }',
            branding: false,
            promotion: false,
            relative_urls: false,
            convert_urls: false
        });
    }

    // Open the item that was just saved / moved
    if (location.hash.indexOf('#item-') === 0) {
        $(location.hash).find('.collapse').addClass('show');
    }
});
</script>
