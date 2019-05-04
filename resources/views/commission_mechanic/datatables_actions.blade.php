{!! Form::open(['route' => ['commission_mechanic.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('commission_mechanic.show', $id) }}" class='btn btn-info btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
</div>
{!! Form::close() !!}
