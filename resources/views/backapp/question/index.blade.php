@extends('layouts.app')

@can('read role')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Data Soal
                @endslot
                @slot('url_sub1')
                    {{ route('home') }}
                @endslot
                @slot('sub1')
                    Dashboard
                @endslot
            @endcomponent

            <section class="section">
                <!-- Role Table -->
                <div class="card border-1 border-primary">
                    <div class="card-header border-bottom p-3">
                        {{-- <h4 class="card-title text-white">Daftar Pengguna</h4> --}}
                        <a href="#" class="btn btn-sm btn-primary" onclick="addData()"><i class="bi bi-plus-circle"></i>
                            Tambah</a>
                    </div>
                    <div class="card-body table-responsive p-4">
                        {!! $dataTable->table() !!}

                    </div>
                </div>
                <!--/ Role Table -->

                <!-- Form Role Modal -->
                @include('backapp.question.create')
                @include('backapp.question.edit')
                @include('backapp.question.show_file')
                <!--/ Form Role Modal -->

                <!--Delete Modal -->
                @include('utils.ajaxDelete')
            </section>
        </div>
    @endsection

    @push('scriptjs')
        <script>
            $('.modal').on('shown.bs.modal', function() {
                $(this).find('[autofocus]').focus();
            });
            $('table').on('draw.dt', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            })

            function clearForm() {
                $('[name="soal_type"]').val("pilihan");
                $('[name="mapel_id"]').val("");
                $('[name="file"]').val("");
                $('[name="soal"]').summernote('reset');
                $('[name="opsi_a"]').summernote('reset');
                $('[name="opsi_b"]').summernote('reset');
                $('[name="opsi_c"]').summernote('reset');
                $('[name="opsi_d"]').summernote('reset');
                $('[name="jawaban"]').val("");
                $('[name="bobot"]').val("");
            }

            $('.btnCancel').on('click', function() {
                clearForm();
                $('#formModal').modal('hide');
                $('#formEditModal').modal('hide');
            })

            $('.viewFileClose').on('click', function() {
                $('#viewFileSoal').html('');
            })

            function viewFile(filename, type) {
                $('#fileModal').modal('show');
                const imgType = ['jpg', 'jpeg', 'png'];
                const audioType = ['mp3', 'wav'];
                let folder = "{{ asset('storage/fileSoal') }}";
                if (imgType.includes(type)) {
                    let img = `<img src="${folder}/${filename}" width="100%">`;
                    $('#viewFileSoal').html(img);
                } else if (audioType.includes(type)) {
                    let audio =
                        `<audio controls><source src="${folder}/${filename}" type="audio/${type}"></audio>`;
                    $('#viewFileSoal').html(audio);
                }
            }

            function addData() {
                clearForm();
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
                $('#formModal').modal('show');
            }

            function editData(id) {
                $('#formEditModal').modal('show');
                var link = "{{ route('questions.edit', ':id') }}";
                link = link.replace(':id', id);

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    data: {
                        id: id
                    },

                    success: function(response) {
                        window.scrollTo(0, 0);
                        $("#btnSaveEdit").html('<i class="bi bi-save"></i> Update');
                        $("#saveEditSoal").attr('action', response.link);
                        $('#soal_type_edit').val(response.data.soal_type);
                        $('#mapel_id_edit').val(response.data.mapel_id);
                        $('#soal_edit').summernote('code', response.data.soal);
                        $('#opsi_a_edit').summernote('code', response.data.opsi_a);
                        $('#opsi_b_edit').summernote('code', response.data.opsi_b);
                        $('#opsi_c_edit').summernote('code', response.data.opsi_c);
                        $('#opsi_d_edit').summernote('code', response.data.opsi_d);
                        $('#jawaban_edit').val(response.data.jawaban);
                        $('#bobot_edit').val(response.data.bobot);
                        $('#teacher_id_edit').val(response.data.teacher_id);
                        if (response.data.file != null) {
                            let html =
                                `<a href="#" class="btn btn-sm btn-outline-secondary mb-2" onclick="viewFile('${response.data.file}','${response.data.file_type}')"><i class="bi bi-eye"></i> Lihat File</a>`;
                            $('#viewFileSoalEdit').html(html);
                        }
                    },
                    error: function(response) {
                        toastr.error('Terjadi kesalahan', 'ERROR');
                    },
                });
            }

            $('#saveSoal').on('submit', function(e) {
                if ($('[name="mapel_id"]').val() == "") {
                    toastr.error('Silahkan pilih Mata Pelajaran', 'ERROR');
                    e.preventDefault();
                } else if ($('[name="soal"]').summernote('isEmpty')) {
                    toastr.error('Soal tidak boleh kosong', 'ERROR');
                    e.preventDefault();
                } else if ($('[name="opsi_a"]').summernote('isEmpty')) {
                    toastr.error('Opsi A tidak boleh kosong', 'ERROR');
                    e.preventDefault();
                } else if ($('[name="opsi_b"]').summernote('isEmpty')) {
                    toastr.error('Opsi B tidak boleh kosong', 'ERROR');
                    e.preventDefault();
                } else if ($('[name="opsi_c"]').summernote('isEmpty')) {
                    toastr.error('Opsi C tidak boleh kosong', 'ERROR');
                    e.preventDefault();
                } else if ($('[name="opsi_d"]').summernote('isEmpty')) {
                    toastr.error('Opsi D tidak boleh kosong', 'ERROR');
                    e.preventDefault();
                } else if ($('[name="jawaban"]').val() == "") {
                    toastr.error('Silahkan pilih Kunci Jawaban', 'ERROR');
                    e.preventDefault();
                } else if ($('[name="bobot"]').val() == "") {
                    toastr.error('Bobot tidak boleh kosong', 'ERROR');
                    e.preventDefault();
                } else {
                    e.preventDefault();
                    let spin =
                        '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                    $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');

                    let link = "{{ route('questions.store') }}";
                    let type = "POST";

                    let data = new FormData(this);

                    $.ajax({
                        url: link,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: type,
                        dataType: 'json',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $("#saveSoal").attr('action', '');
                            $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                            //delete field
                            clearForm();
                            $('#formModal').modal('hide');
                            window.scrollTo(0, document.body.scrollHeight);
                            $('#soal-table').DataTable().ajax.reload(null, false);
                            toastr.success(response.message, 'SUCCESS');
                        },
                        error: function(response) {
                            $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                            toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                        },
                    });
                }
            });

            $('#saveEditSoal').on('submit', function(e) {
                if ($('#mapel_id_edit').val() == "") {
                    toastr.error('Silahkan pilih Mata Pelajaran', 'ERROR');
                    e.preventDefault();
                } else if ($('#soal_edit').summernote('isEmpty')) {
                    toastr.error('Soal tidak boleh kosong', 'ERROR');
                    e.preventDefault();
                } else if ($('#opsi_a_edit').summernote('isEmpty')) {
                    toastr.error('Opsi A tidak boleh kosong', 'ERROR');
                    e.preventDefault();
                } else if ($('#opsi_b_edit').summernote('isEmpty')) {
                    toastr.error('Opsi B tidak boleh kosong', 'ERROR');
                    e.preventDefault();
                } else if ($('#opsi_c_edit').summernote('isEmpty')) {
                    toastr.error('Opsi C tidak boleh kosong', 'ERROR');
                    e.preventDefault();
                } else if ($('#opsi_d_edit').summernote('isEmpty')) {
                    toastr.error('Opsi D tidak boleh kosong', 'ERROR');
                    e.preventDefault();
                } else if ($('#jawaban_edit').val() == "") {
                    toastr.error('Silahkan pilih Kunci Jawaban', 'ERROR');
                    e.preventDefault();
                } else if ($('#bobot_edit').val() == "") {
                    toastr.error('Bobot tidak boleh kosong', 'ERROR');
                    e.preventDefault();
                } else {
                    e.preventDefault();
                    let spin =
                        '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                    $("#btnSaveEdit").html(spin + ' Processing...').attr('disabled', 'disabled');

                    var link = $("#saveEditSoal").attr('action');
                    let type = "POST";

                    let data = new FormData(this);

                    $.ajax({
                        url: link,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: type,
                        dataType: 'json',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $("#saveEditSoal").attr('action', '');
                            $("#btnSaveEdit").html('<i class="bi bi-save"></i> Update').removeAttr(
                                'disabled');
                            //delete field
                            clearForm();
                            $('#formEditModal').modal('hide');
                            window.scrollTo(0, document.body.scrollHeight);
                            $('#soal-table').DataTable().ajax.reload(null, false);
                            toastr.success(response.message, 'SUCCESS');
                        },
                        error: function(response) {
                            $("#btnSaveEdit").html('<i class="bi bi-save"></i> Update').removeAttr(
                                'disabled');
                            toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                        },
                    });
                }
            });

            $(document).on('click', '#deleteSoal', function(e) {
                e.preventDefault();
                let allData = new FormData(this);
                var linkDel = $(this).attr('action');
                $('#deleteModal').modal('show');
                $("#btnYes").click(function() {
                    $.ajax({
                        url: linkDel,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: allData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            linkDel = '';
                            $('#deleteModal').modal('hide');
                            $('#soal-table').DataTable().ajax.reload(null, false);
                            $('[name="kode"]').focus();
                            if (response) {
                                toastr.success('Berhasil hapus', 'SUCCESS');
                            }
                            if (!response) {
                                toastr.warning('Tidak Bisa Dihapus', 'WARNING');
                            }
                        },

                    });
                });
                $(".btnBatal").click(function() {
                    linkDel = '';
                    $('#deleteModal').modal('hide');
                });
            });
        </script>

        {!! $dataTable->scripts() !!}
    @endpush

@endcan
