<a href="javascript:void(0)" data-toggle="tooltip"  data-id="{{ $id }}" data-original-title="Edit" title="Edit" class="edit text-info edit-company rounded-0 py-1 px-2">
    <i class="far fa-edit"></i>
</a>
<a href="javascript:void(0);" id="delete-company" data-toggle="tooltip" data-original-title="Delete"  title="Delete" data-id="{{ $id }}" class="delete text-danger rounded-0 py-1 px-2">
    <i class="fas fa-trash-alt"></i>
</a>

@if($status)
    <a href="javascript:void(0);" id="status-company" data-toggle="tooltip" data-original-title="Lock" title="Lock" data-id="{{ $id }}" class="status text-success rounded-0 py-1 px-2">
        <i class="fas fa-lock-open"></i> 
    </a>
@else
    <a href="javascript:void(0);" id="status-company" data-toggle="tooltip" data-original-title="Unlock" title="Unlock" data-id="{{ $id }}" class="status text-warning rounded-0 py-1 px-2">
        <i class="fas fa-lock"></i>
    </a>
@endif