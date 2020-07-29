@if (session()->has('success'))
<div class="alert alert-success text-capitalize text-bold">
    {{session()->get('success')}}
</div>
@elseif(session()->has('warning'))
<div class="alert alert-warning text-capitalize text-bold">
    {{session()->get('warning')}}
</div>
@elseif(session()->has('danger'))
<div class="alert alert-danger text-capitalize text-bold">
    {{session()->get('danger')}}
</div>
@elseif(session()->has('fatal'))
<div class="alert bg-dark text-white text-capitalize text-bold">
    {{session()->get('fatal')}}
</div>
@else
@endif