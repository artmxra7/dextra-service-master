{!! Form::open(['route' => ['commission_sales.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('commission_sales.show', $id) }}" class='btn btn-info btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
</div>
{!! Form::close() !!}
