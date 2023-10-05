<div class="modal fade" id="formEditModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="saveEditSoal" action="" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h4 class="modal-title mt-n3">Edit Soal</h4>
                    <button type="button" class="btn-close btnCancel"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger" style="margin-top:-15px;">* Wajib diisi</p>
                    <div class="row p-1">
                        <div class="form-group col-6 mb-3">
                            <label for="soal_type_edit" class="form-label">Tipe Soal<span
                                    class="text-danger">*</span></label>
                            <select name="soal_type" id="soal_type_edit" class="form-select" required>
                                <option value="pilihan">Pilihan Ganda</option>
                                <option value="esai">Esai</option>
                                <option value="isian">Isian</option>
                            </select>
                        </div>
                        <div class="form-group col-6 mb-3">
                            <label for="mapel_id_edit" class="form-label">Mata Pelajaran<span
                                    class="text-danger">*</span></label>
                            <select name="mapel_id" id="mapel_id_edit" class="form-select">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach (Helper::mapel() as $item)
                                    <option value="{{ $item->id }}">{{ $item->code . '-' . $item->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 mb-3">
                            <label for="soal_edit" class="form-group">Soal<span class="text-danger">*</span></label>
                            <div class="mb-2">
                                <div id="viewFileSoalEdit"></div>
                                <input type="file" name="file" class="form-control"
                                    accept=".jpg, .jpeg, .png, .mp3, .wav">
                                <small class="text-danger">Ukuran maksimal file <strong>2 MB</strong>. Tipe file yang
                                    diizinkan <strong>.jpg .jpeg .png .mp3 .wav</strong></small>
                            </div>
                            <textarea class="summernote" name="soal" id="soal_edit"></textarea>
                        </div>
                        <div class="form-group col-6 mb-3">
                            <label for="opsi_a_edit" class="form-group">Opsi A<span class="text-danger">*</span></label>
                            <textarea class="summernote" name="opsi_a" id="opsi_a_edit"></textarea>
                        </div>
                        <div class="form-group col-6 mb-3">
                            <label for="opsi_b_edit" class="form-group">Opsi B<span class="text-danger">*</span></label>
                            <textarea class="summernote" name="opsi_b" id="opsi_b_edit"></textarea>
                        </div>
                        <div class="form-group col-6 mb-3">
                            <label for="opsi_c_edit" class="form-group">Opsi C<span class="text-danger">*</span></label>
                            <textarea class="summernote" name="opsi_c" id="opsi_c_edit"></textarea>
                        </div>
                        <div class="form-group col-6 mb-3">
                            <label for="opsi_d_edit" class="form-group">Opsi D<span class="text-danger">*</span></label>
                            <textarea class="summernote" name="opsi_d" id="opsi_d_edit"></textarea>
                        </div>
                        {{-- <div class="form-group col-6 mb-2">
                            <label for="opsi_e" class="form-group">Opsi E</label>
                            <textarea class="summernote" name="opsi_e" id="opsi_e"></textarea>
                        </div> --}}
                        <div class="form-group col-6 mb-2">
                            <label for="jawaban_edit" class="form-label">Kunci Jawaban<span
                                    class="text-danger">*</span></label>
                            <select name="jawaban" id="jawaban_edit" class="form-select">
                                <option value="">Pilih Jawaban</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                        <div class="form-group col-6 mb-2">
                            <label for="bobot_edit" class="form-group">Bobot<span class="text-danger">*</span></label>
                            <input type="text" class="form-control bobot" id="bobot_edit" name="bobot">
                            <input type="hidden" class="form-control" id="teacher_id_edit" name="teacher_id"
                                value="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-icon icon-left btnCancel" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-icon icon-left btn-primary" id="btnSaveEdit">
                        <i class="bi bi-save"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
