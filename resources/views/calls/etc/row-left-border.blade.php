@if($rating =='N/A' )
    {{ 'left-border-warning' }}
@elseif($rating < 3)
    {{ 'left-border-danger' }}
@else
    {{ 'left-border-success' }}
@endif
