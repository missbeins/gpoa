<!-- Modal -->
    <div class="modal fade" id="reason{{ $disapproved_event->upcoming_event_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-warning">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Event Disapproval Reason/s</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                                             
                        <div class="form-floating">
                            <textarea class="form-control"id="reason" style="height: 100px" readonly>{{ $disapproved_event->reason }}</textarea>
                            <label for="reason">Reason</label>
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
