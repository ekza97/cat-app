<div class="modal fade text-left" id="formModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <form id="saveSetMapel" action="{{ route('teachers.set_mapel') }}" method="post">
                @csrf <div class="modal-header">
                    <h4 class="modal-title mt-n3 pb-0">Atur Mata Pelajaran</h4>
                    <button type="button" class="btn-close btnCancel" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="teacher_id">
                        @foreach (Helper::mapel() as $row)
                            <div class="form-check me-2">
                                <div class="checkbox">
                                    <input type="checkbox" class="form-check-input check" value="{{ $row->id }}"
                                        id="{{ $row->id }}" name="mapel[]">
                                    <label for="{{ $row->id }}">{{ $row->code . '-' . $row->description }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-icon icon-left btnCancel" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-icon icon-left btn-primary" id="btnSetMapel">
                        <i class="bi bi-save"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
