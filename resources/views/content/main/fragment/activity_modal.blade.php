<div id="activity-modal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body row">
        <div class="col-md-12 form-group">
          <label>Nama Activity</label>
          <input type="text" name="name" placeholder="Nama Activity" class="form-control">
        </div>
        <div class="col-md-12 form-group">
          <label>Metode</label>
          <select name="method_id" class="form-control">
            <option selected disabled>-- Pilih salah satu --</option>
          </select>
        </div>
        <div class="col-md-12 form-group">
          <label>Tanggal</label>  
          <input type="text" name="daterange" class="form-control" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="remove">Hapus</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" id="submit" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>