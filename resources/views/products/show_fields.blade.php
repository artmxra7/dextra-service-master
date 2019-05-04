<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $product->id !!}</p>
</div>

<!-- Title Field -->
<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    <p>{!! $product->title !!}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{!! $product->slug !!}</p>
</div>

<!-- No Product Field -->
<div class="form-group">
    {!! Form::label('no_product', 'No Product:') !!}
    <p>{!! $product->no_product !!}</p>
</div>

<!-- Sn Product Field -->
<div class="form-group">
    {!! Form::label('sn_product', 'Sn Product:') !!}
    <p>{!! $product->sn_product !!}</p>
</div>

<!-- Photo Field -->
<div class="form-group">
    {!! Form::label('photo', 'Photo:') !!}
    @php
        if (isset($product)) {
            $photo = explode(',', $product->photo);
        }
    @endphp
    <div class="photo-list">
        <div class="photo-item">
            @if (isset($photo[0]))
                <img src="{{ url('attachments/products/' . $photo[0]) }}" class="photo" />
            @else
                <img src="" class="photo" />
            @endif
        </div>
        <div class="photo-item">
            @if (isset($photo[1]))
                <img src="{{ url('attachments/products/' . $photo[1]) }}" class="photo" />
            @else
                <img src="" class="photo" />
            @endif
        </div>
        <div class="photo-item">
            @if (isset($photo[2]))
                <img src="{{ url('attachments/products/' . $photo[2]) }}" class="photo" />
            @else
                <img src="" class="photo" />
            @endif
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $product->description !!}</p>
</div>

<!-- Price Piece Field -->
<div class="form-group">
    {!! Form::label('price_piece', 'Price Piece:') !!}
    <p>{!! $product->price_piece !!}</p>
</div>

<!-- Price Box Field -->
<div class="form-group">
    {!! Form::label('price_box', 'Price Box:') !!}
    <p>{!! $product->price_box !!}</p>
</div>

<!-- Is Active Field -->
<div class="form-group">
    {!! Form::label('is_active', 'Is Active:') !!}
    <p>{!! $product->is_active !!}</p>
</div>

<!-- Product Unit Model Id Field -->
<div class="form-group">
    {!! Form::label('product_unit_model_id', 'Product Unit Model Id:') !!}
    <p>{!! $product->product_unit_model_id !!}</p>
</div>

<!-- Product Brand Id Field -->
<div class="form-group">
    {!! Form::label('product_brand_id', 'Product Brand Id:') !!}
    <p>{!! $product->product_brand_id !!}</p>
</div>
