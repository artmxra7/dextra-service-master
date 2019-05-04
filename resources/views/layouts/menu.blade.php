<li class="{{ Request::is('newsCategories*') ? 'active' : '' }}">
    <a href="{!! route('newsCategories.index') !!}"><i class="fa fa-edit"></i><span>News Categories</span></a>
</li>

<li class="{{ Request::is('news') || Request::is('news/*') ? 'active' : '' }}">
    <a href="{!! route('news.index') !!}"><i class="fa fa-edit"></i><span>News</span></a>
</li>

<li class="{{ Request::is('productBrands*') ? 'active' : '' }}">
    <a href="{!! route('productBrands.index') !!}"><i class="fa fa-edit"></i><span>Product Brands</span></a>
</li>

<li class="{{ Request::is('productUnitModels*') ? 'active' : '' }}">
    <a href="{!! route('productUnitModels.index') !!}"><i class="fa fa-edit"></i><span>Product Unit Models</span></a>
</li>

<li class="{{ Request::is('products*') ? 'active' : '' }}">
    <a href="{!! route('products.index') !!}"><i class="fa fa-edit"></i><span>Products</span></a>
</li>

<li class="{{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="fa fa-edit"></i><span>Users</span></a>
</li>

<li class="{{ Request::is('coupons*') ? 'active' : '' }}">
    <a href="{!! route('coupons.index') !!}"><i class="fa fa-edit"></i><span>Coupons</span></a>
</li>

<li class="{{ Request::is('order_sparepart*') ? 'active' : '' }}">
    <a href="{!! route('order_sparepart.index') !!}"><i class="fa fa-edit"></i><span>Order Products</span></a>
</li>

<li class="{{ Request::is('order_job*') ? 'active' : '' }}">
    <a href="{!! route('order_job.index') !!}"><i class="fa fa-edit"></i><span>Order Job</span></a>
</li>

<li class="{{ Request::is('job_categories*') ? 'active' : '' }}">
    <a href="{!! route('job_categories.index') !!}"><i class="fa fa-edit"></i><span>Job Categories</span></a>
</li>

<li class="{{ Request::is('commission_sales*') ? 'active' : '' }}">
    <a href="{!! route('commission_sales.index') !!}"><i class="fa fa-edit"></i><span>Commission Sales</span></a>
</li>

<li class="{{ Request::is('commission_mechanic*') ? 'active' : '' }}">
    <a href="{!! route('commission_mechanic.index') !!}"><i class="fa fa-edit"></i><span>Commission Mechanic</span></a>
</li>

<li class="{{ Request::is('settings*') ? 'active' : '' }}">
    <a href="{!! route('settings.index') !!}"><i class="fa fa-edit"></i><span>Settings</span></a>
</li>
