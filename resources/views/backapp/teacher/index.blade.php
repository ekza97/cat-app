@extends('layouts.app')

@can('read permission')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Data Guru
                @endslot
                @slot('url_sub1')
                    {{ route('home') }}
                @endslot
                @slot('sub1')
                    Dashboard
                @endslot
            @endcomponent

            <section class="section row">
                <div class="col-md-4">
                    <!-- Form Teacher Modal -->
                    <div class="card">
                        <form action="" method="post" id="saveTeacher">
                            @csrf
                            <div class="card-header border-bottom p-3 pb-1 mb-4">
                                <h4 class="card-title">Form Guru</h4>
                            </div>
                            <div class="card-body">
                                <p class="text-danger" style="margin-top:-15px;">* Wajib diisi</p>
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label for="nik" class="form-label">NIK<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control nik" id="nik" name="nik"
                                                value="{{ old('nik') }}" tabindex="1" required autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Nama Lengkap<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-capitalize" id="name"
                                                name="name" value="{{ old('name') }}" tabindex="1" required autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <button type="reset" class="btn btn-default" id="btnCancel" tabindex="4"><i
                                        class="bi bi-x-circle"></i>
                                    Batal</button>
                                <button type="submit" class="btn btn-primary" id="btnSave" tabindex="3"><i
                                        class="bi bi-save"></i>
                                    Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!--/ Form Teacher Modal -->
                </div>
                <div class="col-md-8">
                    <!-- Permission Table -->
                    <div class="card border-1 border-primary">
                        <div class="card-header border-bottom p-3 pb-1">
                            <h4 class="card-title">Daftar Guru</h4>
                        </div>
                        <div class="card-body table-responsive p-4">
                            {!! $dataTable->table() !!}

                        </div>
                    </div>
                    <!--/ Permission Table -->
                </div>
                @include('backapp.teacher.mapel')

                <!--Delete Modal -->
                @include('utils.ajaxDelete')
            </section>
        </div>
    @endsection

    @push('scriptjs')
        <script>
            $('table').on('draw.dt', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            })

            $('#btnCancel').on('click', function() {
                $('[name="nik"]').val('');
                $('[name="name"]').val('');
                $('[name="nik"]').focus();
                $("#saveTeacher").attr('action', '');
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
            });

            $('.btnCancel').on('click', function() {
                $('input:checkbox').prop('checked', false);
                $('#formModal').modal('hide');
            })

            function setMapel(id) {
                $('#formModal').modal('show');
                $('[name="teacher_id"]').val(id);

                var link = "{{ route('teachers.show', ':id') }}";
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
                        $("#btnSetMapel").html('<i class="bi bi-save"></i> Simpan');
                        for (let i = 0; i < response.data.length; i++) {
                            $(`#${response.data[i].mapel_id}`).prop('checked', true);
                        }
                    },
                    error: function(response) {
                        toastr.error('Terjadi kesalahan', 'ERROR');
                    },
                });
            }

            function editData(id) {
                $('[name="nik"]').focus();
                var link = "{{ route('teachers.edit', ':id') }}";
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
                        $("#btnSave").html('<i class="bi bi-save"></i> Update');
                        $("#saveTeacher").attr('action', response.link);
                        $('[name="nik"]').val(response.nik);
                        $('[name="name"]').val(response.name);
                    },
                    error: function(response) {
                        toastr.error('Terjadi kesalahan', 'ERROR');
                    },
                });
            }

            $('#saveTeacher').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');
                var link = $("#saveTeacher").attr('action');
                let type = "PUT";
                if (link == "") {
                    link = "{{ route('teachers.store') }}";
                    type = "POST";
                }
                let nik = $('[name="nik"]').val();
                let name = $('[name="name"]').val();

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    dataType: 'json',
                    data: {
                        nik: nik,
                        name: name
                    },
                    success: function(response) {
                        $("#saveTeacher").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        $('[name="nik"]').val('');
                        $('[name="name"]').val('');
                        // window.scrollTo(0, document.body.scrollHeight);
                        $('#teacher-table').DataTable().ajax.reload(null, false);
                        $('[name="nik"]').focus();
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $('#saveSetMapel').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSetMapel").html(spin + ' Processing...').attr('disabled', 'disabled');
                let link = "{{ route('teachers.set_mapel') }}";
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
                        $("#btnSetMapel").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        $('input:checkbox').prop('checked', false);
                        $('#formModal').modal('hide');
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSetMapel").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deleteTeacher', function(e) {
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
                            console.log(response);
                            linkDel = '';
                            $('#deleteModal').modal('hide');
                            $('#teacher-table').DataTable().ajax.reload(null, false);
                            $('[name="nik"]').focus();
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
