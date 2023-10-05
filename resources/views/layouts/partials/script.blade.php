<script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="{{ asset('') }}assets/js/bootstrap.js"></script>
<script src="{{ asset('') }}assets/js/app.js"></script>
<script src="{{ asset('') }}assets/js/pages/horizontal-layout.js"></script>

<!-- Need: Apexcharts -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('') }}assets/extensions/jquery-mask/jquery-mask.min.js"></script>

<script src="{{ asset('') }}assets/extensions/summernote/summernote-lite.min.js"></script>

<script src="{{ asset('') }}assets/extensions/apexcharts/apexcharts.min.js"></script>
<script src="{{ asset('') }}assets/js/pages/dashboard.js"></script>

<script>
    $('.summernote').summernote({
        tabsize: 2,
        height: 80,
        toolbar: [
            ['misc', ['undo', 'redo']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize', 'fontsizeunit']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']], //video,
            ['view', ['help']], //fullscreen,codeview,
        ]
    });
    $('.uang').mask('000.000.000.000.000', {
        reverse: true
    });
    $('.nik').mask('0000000000000000', {
        reverse: true
    });
    $('.nisn').mask('0000000000', {
        reverse: true
    });
    $('.bobot').mask('000', {
        reverse: true
    });
    $('.wa').mask('000-0000-0000', {
        reverse: true
    });
</script>
