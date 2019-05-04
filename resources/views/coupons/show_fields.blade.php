<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $coupon->id !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $coupon->description !!}</p>
</div>

<!-- Percent Field -->
<div class="form-group">
    {!! Form::label('percent', 'Percent:') !!}
    <p>{!! $coupon->percent !!}</p>
</div>

<!-- Coupon Field -->
<div class="form-group">
    {!! Form::label('coupon', 'Coupon:') !!}
    <p>{!! $coupon->coupon !!}</p>
</div>
