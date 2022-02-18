 
  <!-- Modal -->
  <div class="modal fade" id="accept-partnership{{ $request->event_id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Partnership Request Confirmation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('officer.acceptRequest', $request->event_id) }}" enctype="multipart/form-data"  method="POST">
            @csrf
            <div class="modal-body">
              <p>Are you sure you want to accept the request for partnership?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
              <button type="submit" class="btn btn-primary">Yes</button>
            </div>
        </form>
      </div>
    </div>
  </div>