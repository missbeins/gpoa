  <!-- Modal -->
<div class="modal fade" id="edit{{ $breakdown->breakdown_id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Partnership Request Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('officer.update-breakdown',$breakdown->breakdown_id) }}"  method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="projected_budget" value="{{ $upcoming_event->projected_budget }}">
                <input type="hidden" name="event_id" value="{{ $breakdown->event_id }}">

                <div class="modal-body">
                   <div class="row text-start">
                       <div class="col-md-6">Name</div>
                       <div class="col-md-6">Amount</div>
                   </div>
                    <div class="input-group control-group" >
                        <input type="text" name="name" class=" form-control" value="{{ $breakdown->name }}">
                        <input type="number" name="amount" class=" form-control" value="{{ $breakdown->amount }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-defualt" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>