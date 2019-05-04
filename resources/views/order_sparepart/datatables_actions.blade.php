{!! Form::open(['route' => ['order_sparepart.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('order_sparepart.show', $id) }}" class='btn btn-info btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
</div>
{!! Form::close() !!}
