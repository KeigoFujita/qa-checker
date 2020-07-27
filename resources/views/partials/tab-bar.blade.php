<div class="h-100 pb-2 d-flex justify-content-end align-items-end">
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <button type="button" class="btn btn-secondary btn-sm">Calls</button>
        <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ $selected }}
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item"
                    href="{{ route('calls.index',['week'=>1]) }}">Last
                    Week</a>
                <a class="dropdown-item"
                    href="{{ route('calls.index',['week'=>2]) }}">Last
                    last week</a>
            </div>
        </div>
    </div>
</div>
