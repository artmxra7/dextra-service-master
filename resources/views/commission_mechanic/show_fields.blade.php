<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $commission_mechanic->id !!}</p>
</div>

<!-- User Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User:') !!}
    <p>{!! $commission_mechanic->user->name !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('job_category_id', 'Description:') !!}
    <p>{!! $commission_mechanic->description !!}</p>
</div>

<!-- Amount Field -->
<div class="form-group">
    {!! Form::label('job_category_id', 'Amount:') !!}
    <p>{!! $commission_mechanic->amount !!}</p>
</div>

<!-- Type Field -->
<div class="form-group">
    {!! Form::label('location_description', 'Type:') !!}
    <p>{!! $commission_mechanic->type !!}</p>
</div>
