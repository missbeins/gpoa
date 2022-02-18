 
  <!-- Modal -->
  <div class="modal fade" id="apply{{ $available_partnership->upcoming_event_id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Partnership Request Confirmation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('officer.applyPartnership', $available_partnership->upcoming_event_id) }}" enctype="multipart/form-data"  method="POST">
            @csrf
            <input type="hidden" name="organization_id" value="{{ $available_partnership->upcoming_event_id }}">
            <div class="modal-body">
              <p>Are you sure you want to request partnership?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
              <button type="submit" class="btn btn-primary">Yes</button>
            </div>
        </form>
      </div>
    </div>
  </div>