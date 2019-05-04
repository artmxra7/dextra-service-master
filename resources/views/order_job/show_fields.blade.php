<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $order_job->id !!}</p>
</div>

<!-- User Member Field -->
<div class="form-group">
    {!! Form::label('user_member_id', 'User Member:') !!}
    <p>{!! $order_job->user_member->name !!}</p>
</div>

<!-- Job Category Field -->
<div class="form-group">
    {!! Form::label('job_category_id', 'Job Category:') !!}
    <p>{!! $order_job->job_category->name !!}</p>
</div>

<!-- Job Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Job Description:') !!}
    <p>{!! $order_job->description !!}</p>
</div>

<!-- Location Name Field -->
<div class="form-group">
    {!! Form::label('location_name', 'Location Name:') !!}
    <p>{!! $order_job->location_name !!}</p>
</div>

<!-- Location Description Field -->
<div class="form-group">
    {!! Form::label('location_description', 'Location Description:') !!}
    <p>{!! $order_job->location_description !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('location_description', 'Status:') !!}
    <p>{!! $order_job->status !!}</p>
</div>
