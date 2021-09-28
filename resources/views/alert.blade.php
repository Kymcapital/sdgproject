@if(!empty($success))
  <div class="alert alert-success"> {{ $success }}</div>
@endif
@if(!empty($error))
  <div class="alert alert-danger"> {{ $error }}</div>
@endif
@if(!empty($info))
  <div class="alert alert-info"> {{ $info }}</div>
@endif
@if(!empty($warning))
  <div class="alert alert-warning"> {{ $warning }}</div>
@endif

@if(session()->get('success'))
    <div class="alert alert-success mb-3">
        {{ session()->get('success') }}
    </div>
@elseif(session()->get('error'))
    <div class="alert alert-danger mb-3">
        {{ session()->get('error') }}
    </div>
@elseif(session()->get('info'))
    <div class="alert alert-info mb-3">
        {{ session()->get('info') }}
    </div>
@elseif(session()->get('warning'))
    <div class="alert alert-warning mb-3">
        {{ session()->get('warning') }}
    </div>
@endif



<div class="alert d-none"></div>