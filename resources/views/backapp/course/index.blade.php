@extends('layouts.app')

@can('read permission')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Data Mata Pelajaran
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
                    <!-- Form Course Modal -->
                    <div class="card">
                        <form action="" method="post" id="saveCourse">
                            @csrf
                            <div class="card-header border-bottom p-3 pb-1 mb-4">
                                <h4 class="card-title">Form Mapel</h4>
                            </div>
                            <div class="card-body">
                                <p class="text-danger" style="margin-top:-15px;">* Wajib diisi</p>
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label for="code" class="form-label">Kode<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-uppercase" id="code"
                                                name="code" value="{{ old('code') }}" tabindex="1" required autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Nama Mapel<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-capitalize" id="description"
                                                name="description" value="{{ old('description') }}" tabindex="1" required
                                                autofocus>
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
                    <!--/ Form Course Modal -->
                </div>
                <div class="col-md-8">
                    <!-- Permission Table -->
                    <div class="card border-1 border-primary">
                        <div class="card-header border-bottom p-3 pb-1">
                            <h4 class="card-title">Daftar Mata Pelajaran</h4>
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
                $('[name="code"]').val('');
                $('[name="description"]').val('');
                $('[name="code"]').focus();
                $("#saveCourse").attr('action', '');
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
            });

            function editData(id) {
                $('[name="name"]').focus();
                var link = "{{ route('courses.edit', ':id') }}";
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
                        $("#saveCourse").attr('action', response.link);
                        $('[name="code"]').val(response.code);
                        $('[name="description"]').val(response.description);
                    },
                    error: function(response) {
                        toastr.error('Terjadi kesalahan', 'ERROR');
                    },
                });
            }

            $('#saveCourse').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');
                var link = $("#saveCourse").attr('action');
                let type = "PUT";
                if (link == "") {
                    link = "{{ route('courses.store') }}";
                    type = "POST";
                }
                let code = $('[name="code"]').val();
                let description = $('[name="description"]').val();

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    dataType: 'json',
                    data: {
                        code: code,
                        description: description
                    },
                    success: function(response) {
                        $("#saveCourse").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        $('[name="code"]').val('');
                        $('[name="description"]').val('');
                        // window.scrollTo(0, document.body.scrollHeight);
                        $('#mapel-table').DataTable().ajax.reload(null, false);
                        $('[name="code"]').focus();
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deleteCourse', function(e) {
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
                            $('#mapel-table').DataTable().ajax.reload(null, false);
                            $('[name="name"]').focus();
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
