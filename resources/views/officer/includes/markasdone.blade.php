 <!-- Modal -->
 <div class="modal fade" id="mark-as-done-form{{ $upcoming_event->upcoming_event_id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Update Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('officer.mark-as-done', $upcoming_event->upcoming_event_id) }}" enctype="multipart/form-data"  method="POST">
            @csrf
            <div class="modal-body">
                <h5 class="mb-3">Click submit to continue.</h5>
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="title_of_activity">{{ __('Title of Event') }}</span>
                    <input type="text" class="form-control @error('time') is-invalid @enderror" aria-label="Sizing example input" aria-describedby="title_of_activity" name="title_of_activity"
                    value="{{ $upcoming_event->title_of_activity }}" required>
                    @error('tititle_of_activityme')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="date">{{ __('Date') }}</span>
                    <input type="date" class="form-control @error('date') is-invalid @enderror" aria-label="Sizing example input" aria-describedby="date" name="date"
                    value="{{ $upcoming_event->date }}" required>
                    @error('date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                
                
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="time">{{ __('Time') }}</span>
                    <input type="time" class="form-control @error('time') is-invalid @enderror" aria-label="Sizing example input" aria-describedby="time" name="time"
                    value="{{ $upcoming_event->time }}" required>
                    @error('time')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
      </div>
    </div>
  </div>