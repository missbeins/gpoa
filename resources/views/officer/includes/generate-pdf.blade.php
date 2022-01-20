<!-- Modal -->
<form action="{{ route('officer.print-pdf') }}" method="POST">
    @csrf
    <div class="modal fade" id="generate-pdf" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Generate PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label for="formFileSm" class="form-label">Select a semester</label>
                        <select class="form-control @error('semester') is-invalid @enderror" id="inputGroupSelect01" name="semester" required>
                            @foreach ($newsemcollection as $semester)
                                        <option value="{{ $semester->semester }}">{{ $semester->semester }}</option>                          
                            @endforeach
                        </select>                        
                            @error('semester')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    <div class="mb-3">
                        <label for="formFileSm" class="form-label">Select a School Year</label>
                        <select class="form-control @error('school_year') is-invalid @enderror" id="inputGroupSelect01" name="school_year" required>
                            @foreach ($newyearcollection as $year)
                                        <option value="{{ $year->school_year }}">{{ $year->school_year }}</option>                          
                            @endforeach
                        </select>                        
                            @error('school_year')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    <div class="mb-3">
                        <label for="formFileSm" class="form-label">Membership Fee</label>
                        <input type="number" name="membership_fee">                      
                        @error('membership_fee')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        
                    </div>
                    <div class="mb-3">
                       
                        <label for="formFileSm" class="form-label">Total Collection</label>
                        <input type="number" name="total_collection">                      
                        @error('total_collection')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </div>
        </div>
    </div>
</form>