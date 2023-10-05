@extends('layouts.app')

@can('read permission')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Data Siswa
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
                    <!-- Form Permission Modal -->
                    <div class="card">
                        <form action="" method="post" id="saveSiswa">
                            @csrf
                            <div class="card-header border-bottom p-3 pb-1 mb-4">
                                <h4 class="card-title">Form Siswa</h4>
                            </div>
                            <div class="card-body">
                                <p class="text-danger" style="margin-top:-15px;">* Wajib diisi</p>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="kelas_id" class="form-label">Kelas<span class="text-danger">*</span></label>
                                        <select name="kelas_id" id="kelas_id" class="form-select" required>
                                            <option value="">Pilih Kelas</option>
                                            @foreach ($kelas as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="nis" class="form-label">NIS/NISN<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control nisn" id="nis" name="nis"
                                                value="{{ old('nis') }}" tabindex="1" required autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Nama Siswa<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-capitalize" id="name"
                                                name="name" value="{{ old('name') }}" tabindex="2" required>
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
                    <!--/ Form Permission Modal -->
                </div>
                <div class="col-md-8">
                    <!-- Permission Table -->
                    <div class="card border-1 border-primary">
                        <div class="card-header border-bottom p-3 pb-1 justify-content-between">
                            <h4 class="card-title float-start">Daftar Siswa</h4>
                            {{-- <div class="float-end pb-1">
                                <button class="btn btn-primary btn-icon icon-left btn-sm">
                                    <i class="bi bi-file-earmark-arrow-up"></i>
                                    Import
                                </button>
                            </div> --}}
                        </div>
                        <div class="card-body table-responsive p-4">
                            {!! $dataTable->table() !!}

                        </div>
                    </div>
                    <!--/ Permission Table -->
                </div>


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
                $('[name="kelas_id"]').val('');
                $('[name="nis"]').val('');
                $('[name="name"]').val('');
                $('[name="nis"]').focus();
                $("#saveSiswa").attr('action', '');
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
            });

            function editData(id) {
                $('[name="nis"]').focus();
                var link = "{{ route('students.edit', ':id') }}";
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
                        $("#saveSiswa").attr('action', response.link);
                        $('[name="kelas_id"]').val(response.data.kelas_id);
                        $('[name="nis"]').val(response.data.nis);
                        $('[name="name"]').val(response.data.name);
                    },
                    error: function(response) {
                        toastr.error('Terjadi kesalahan', 'ERROR');
                    },
                });
            }

            $('#saveSiswa').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');
                var link = $("#saveSiswa").attr('action');
                let type = "PUT";
                if (link == "") {
                    link = "{{ route('students.store') }}";
                    type = "POST";
                }
                let kelas_id = $('[name="kelas_id"]').val();
                let nis = $('[name="nis"]').val();
                let name = $('[name="name"]').val();

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    dataType: 'json',
                    data: {
                        kelas_id: kelas_id,
                        nis: nis,
                        name: name
                    },
                    success: function(response) {
                        $("#saveSiswa").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        $('[name="kelas_id"]').val('');
                        $('[name="nis"]').val('');
                        $('[name="name"]').val('');
                        // window.scrollTo(0, document.body.scrollHeight);
                        $('#student-table').DataTable().ajax.reload(null, false);
                        $('[name="nis"]').focus();
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deleteSiswa', function(e) {
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
                            $('#student-table').DataTable().ajax.reload(null, false);
                            $('[name="nis"]').focus();
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
