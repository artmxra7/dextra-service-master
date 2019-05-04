@if ($role == 'member')
  customer
@else
  {{ $role }}
@endif
