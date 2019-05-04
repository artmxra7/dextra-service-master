{!! Form::open(['route' => ['order_job.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('order_job.show', $id) }}" class='btn btn-info btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
</div>
{!! Form::close() !!}
