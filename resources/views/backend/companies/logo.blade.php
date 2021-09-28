@if($logo)
    <img id="preview" src="{{ ('images/company/'.$logo) }}" alt="Preview" style="object-fit: contain;" class="hidden" width="35" height="35">
@else
    <img id="preview" src="https://via.placeholder.com/150" alt="Preview" style="object-fit: contain;" class="hidden" width="35" height="35">
@endif