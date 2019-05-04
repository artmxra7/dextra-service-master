<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $commission_sales->id !!}</p>
</div>

<!-- User Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User:') !!}
    <p>{!! $commission_sales->user->name !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('job_category_id', 'Description:') !!}
    <p>{!! $commission_sales->description !!}</p>
</div>

<!-- Amount Field -->
<div class="form-group">
    {!! Form::label('job_category_id', 'Amount:') !!}
    <p>{!! $commission_sales->amount !!}</p>
</div>

<!-- Type Field -->
<div class="form-group">
    {!! Form::label('location_description', 'Type:') !!}
    <p>{!! $commission_sales->type !!}</p>
</div>
