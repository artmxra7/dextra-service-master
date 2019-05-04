<!-- Key Field -->
<div class="form-group col-sm-12">
    {!! Form::label('value', 'Key:') !!}
    {!! Form::text('key', null, ['class' => 'form-control', 'maxlength' => 30, 'required' => true ]) !!}
</div>
<!-- Value Field -->
<div class="form-group col-sm-12">
    {!! Form::label('value', 'Value:') !!}
    {!! Form::text('value', null, ['class' => 'form-control', 'maxlength' => 30, 'required' => true ]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('settings.index') !!}" class="btn btn-default">Cancel</a>
</div>
