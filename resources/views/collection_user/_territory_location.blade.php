<div class="col-md-12">
    <label for="assign_location">Assign Location<span class="mandatory">*</span></label>    
</div>
<div class="col-md-12">
<select id="assign_location" class="form-control" placeholder="Assign Location" name="location[]" multiple="multiple">    
    @if(isset($locations))    
    @foreach($locations as $loc)
        <option value="{{ $loc->id }}" >{{ $loc->area_name }}</option>
    @endforeach
    @endif  
</select>              
</div>