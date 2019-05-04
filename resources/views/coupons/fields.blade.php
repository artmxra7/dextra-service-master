
<!-- Coupon Field -->
<div class="form-group col-sm-6">
    {!! Form::label('coupon', 'Coupon:') !!}
    {!! Form::text('coupon', null, ['class' => 'form-control','required' => true]) !!}
</div>

<!-- Percent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('percent', 'Percent:') !!}
    <div class="input-group">
        {!! Form::number('percent', 0.1, ['class' => 'form-control', 'min' => '0.1', 'required' => true ,'step' => '0.1']) !!}
        <span class="input-group-addon" id="basic-addon1">%</span>
    </div>
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('coupons.index') !!}" class="btn btn-default">Cancel</a>
</div>
