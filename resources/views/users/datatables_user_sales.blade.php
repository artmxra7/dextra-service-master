@if($user_sales_name == '-')
    {{ $user_sales_name }}
@else
    <a href="{{ route('users.show', $user_sales_id) }}">{{ $user_sales_name }}</a>
@endif
