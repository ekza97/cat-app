@extends('layouts.app')

@can('read role')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Ujian
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
                @include('backapp.exam.create')
                @include('backapp.exam.edit')
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
                $('[name="teacher_id"]').val("");
                $('[name="mapel_id"]').val("");
                $('[name="name"]').val("");
                $('[name="jml_soal"]').val("");
                $('[name="jml_waktu"]').val("");
                $('[name="type"]').val("");
                $('[name="exam_start_date"]').val("");
                $('[name="exam_start_time"]').val("");
                $('[name="exam_end_date"]').val("");
                $('[name="exam_end_time"]').val("");
            }

            $('.btnCancel').on('click', function() {
                clearForm();
                $('#formModal').modal('hide');
                $('#formEditModal').modal('hide');
            })

            const getDateFromString = str => {
                const [date, time] = str.split(" ");
                return date;
            };
            const getTimeFromString = str => {
                const [date, time] = str.split(" ");
                return time;
            };

            function addData() {
                clearForm();
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
                $('#formModal').modal('show');
            }

            function editData(id) {
                $('#formEditModal').modal('show');
                var link = "{{ route('exams.edit', ':id') }}";
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
                        $("#saveExamEdit").attr('action', response.link);
                        $('#name_edit').val(response.data.name);
                        $('#mapel_id_edit').val(response.data.mapel_id);
                        $('#jml_soal_edit').val(response.data.jml_soal);
                        $('#jml_waktu_edit').val(response.data.jml_waktu);
                        $('#exam_start_date_edit').val(getDateFromString(response.data.exam_start));
                        $('#exam_end_date_edit').val(getDateFromString(response.data.exam_end));
                        $('#exam_start_time_edit').val(getTimeFromString(response.data.exam_start));
                        $('#exam_end_time_edit').val(getTimeFromString(response.data.exam_end));
                        $('#type_edit').val(response.data.type);
                        console.log(getDateFromString(response.data.exam_start));
                        console.log(getTimeFromString(response.data.exam_start));
                    },
                    error: function(response) {
                        toastr.error('Terjadi kesalahan', 'ERROR');
                    },
                });
            }

            $('#saveExam').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');

                let link = "{{ route('exams.store') }}";
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
                        $('#exam-table').DataTable().ajax.reload(null, false);
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $('#saveExamEdit').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSaveEdit").html(spin + ' Processing...').attr('disabled', 'disabled');

                var link = $("#saveExamEdit").attr('action');
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
                        $("#saveExamEdit").attr('action', '');
                        $("#btnSaveEdit").html('<i class="bi bi-save"></i> Update').removeAttr(
                            'disabled');
                        //delete field
                        clearForm();
                        $('#formEditModal').modal('hide');
                        window.scrollTo(0, document.body.scrollHeight);
                        $('#exam-table').DataTable().ajax.reload(null, false);
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSaveEdit").html('<i class="bi bi-save"></i> Update').removeAttr(
                            'disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deleteExam', function(e) {
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
                            $('#exam-table').DataTable().ajax.reload(null, false);
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
