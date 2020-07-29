<form action="{{ route('calls.index') }}" id="owner-form">
    <div class="pb-5 d-flex justify-content-end align-items-end md-d-block">
        <div class="form-inline mr-1 md-mr-0 md-mb-half">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-secondary text-white md-w-5">Calls</div>
                </div>

                <select name="week_ago" class="form-control form-control-sm" onchange="$('#owner-form').submit()">
                    <option value="0" @include('calls.etc.check-if-same',['selected'=>
                        $week_ago,'option'=>0])>This week</option>
                    <option value="1" @include('calls.etc.check-if-same',['selected'=>
                        $week_ago,'option'=>1])>Last week</option>
                    <option value="2" @include('calls.etc.check-if-same',['selected'=>
                        $week_ago,'option'=>2])>2 weeks ago</option>
                </select>
            </div>
        </div>
        <div class="form-inline">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend ">
                    <div class="input-group-text bg-secondary text-white md-w-5">Owner</div>
                </div>

                <select name="account_id" class="form-control form-control-sm" onchange="$('#owner-form').submit()">
                    <option value="0">All</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->account_id }}" @include('calls.etc.check-if-same',['selected'=>
                            $account_id,'option'=>$account->account_id])>
                            {{ $account->account_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</form>
