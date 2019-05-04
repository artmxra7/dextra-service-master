<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $order_sparepart->id !!}</p>
</div>

<!-- User Member Field -->
<div class="form-group">
    {!! Form::label('user_member_id', 'User Member:') !!}
    <p>{!! $order_sparepart->user_member->name !!}</p>
</div>

<!-- User Sales Field -->
<div class="form-group">
    {!! Form::label('user_sales_id', 'User Sales:') !!}
    <p>{!! ($order_sparepart->user_sales) ? $order_sparepart->user_sales->name : '-' !!}</p>
</div>

<!-- Total Price Field -->
<div class="form-group">
    {!! Form::label('total_price', 'Total Price:') !!}
    <p>{!! $order_sparepart->total_price !!}</p>
</div>

<!-- Address Field -->
<div class="form-group">
    {!! Form::label('address', 'Address:') !!}
    <p>{!! $order_sparepart->address !!}</p>
</div>

<!-- City Field -->
<div class="form-group">
    {!! Form::label('city', 'City:') !!}
    <p>{!! $order_sparepart->city !!}</p>
</div>

<!-- Notes Field -->
<div class="form-group">
    {!! Form::label('notes', 'Notes:') !!}
    <p>{!! $order_sparepart->city !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('notes', 'Status:') !!}
    <p>{!! $order_sparepart->status !!}</p>
</div>
