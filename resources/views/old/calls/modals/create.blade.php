<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add a Call</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('calls.store') }}" id="add-form">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label font-weight-bold">Company</label>
                        <select name="company_id" class="form-control">
                            <option value="none">Please select</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rating" class="col-form-label font-weight-bold">Rating</label>
                        <input type="range" class="form-control-range" name="rating" min="1" max="5">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="duration" class="col-form-label font-weight-bold">Duration</label>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <input type="number" class="form-control" name="duration[]" value="6" min="1"
                                        max="30">
                                    <small id="passwordHelpBlock" class="form-text text-muted">
                                        Minutes
                                    </small>
                                </div>
                                <div class="form-group col-6">
                                    <input type="number" class="form-control" name="duration[]" value="00" min="00"
                                        max="59">
                                    <small id="passwordHelpBlock" class="form-text text-muted">
                                        Seconds
                                    </small>
                                </div>
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="amount_earned" class="col-form-label font-weight-bold">USD</label>
                                <input type="number" class="form-control" name="amount_earned" step="0.01" min="0.2"
                                    max="6" value="3">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button form="add-form" type="submit" class="btn btn-sm btn-success">Add Call</button>
            </div>
        </div>
    </div>
</div>
