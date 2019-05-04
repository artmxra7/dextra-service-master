<!-- Title Field -->
<div class="form-group col-sm-12">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control', 'required' => true]) !!}
</div>

<!-- No Product Field -->
<div class="form-group col-sm-6">
    {!! Form::label('no_product', 'No Product:') !!}
    {!! Form::text('no_product', null, ['class' => 'form-control' , 'required' => true]) !!}
</div>

<!-- Sn Product Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sn_product', 'Sn Product:') !!}
    {!! Form::text('sn_product', null, ['class' => 'form-control' , 'required' => true]) !!}
</div>

@php
    if (isset($product)) {
        $photo = explode(',', $product->photo);
    }
@endphp

<!-- Photo Field -->
<div class="form-group col-sm-12" align="center">
    {!! Form::label('photo', 'Photo:') !!}
    <!--{!! Form::file('photo', null, ['class' => 'form-control']) !!}-->
    <!-- This wraps the whole cropper -->
    <div class="photo-list">
        <div class="photo-item">
            @if (isset($photo[0]))
                <img src="{{ url('attachments/products/' . $photo[0]) }}" class="photo" />
            @else
                <img src="" class="photo" />
            @endif
            <span>+</span>
        </div>
        <div class="photo-item">
            @if (isset($photo[1]))
                <img src="{{ url('attachments/products/' . $photo[1]) }}" class="photo" />
            @else
                <img src="" class="photo" />
            @endif
            <span>+</span>
        </div>
        <div class="photo-item">
            @if (isset($photo[2]))
                <img src="{{ url('attachments/products/' . $photo[2]) }}" class="photo" />
            @else
                <img src="" class="photo" />
            @endif
            <span>+</span>
        </div>
    </div>

    <div class="file-upload">
        <input type="file" name="photo_1" class="picker" />
        <input type="file" name="photo_2" class="picker" />
        <input type="file" name="photo_3" class="picker" />
    </div>
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control rich-text']) !!}
</div>

<!-- Price Piece Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price_piece', 'Price Piece:') !!}
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">Rp</span>
        {!! Form::text('price_piece', null, ['class' => 'form-control currency-mask', 'required' => true ]) !!}
    </div>
</div>

<!-- Price Box Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price_box', 'Price Box:') !!}
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">Rp</span>
        {!! Form::text('price_box', null, ['class' => 'form-control currency-mask', 'required' => true ]) !!}
    </div>
</div>


<!-- Product Unit Model Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('product_unit_model_id', 'Product Unit Model:') !!}
    {!! Form::select('product_unit_model_id', $product_unit_models, null, ['class' => 'form-control', 'required' => true, 'placeholder' => '-- Choose one --']) !!}
</div>

<!-- Product Brand Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('product_brand_id', 'Product Brand:') !!}
    {!! Form::select('product_brand_id', $product_brands, null, ['class' => 'form-control', 'required' => true, 'placeholder' => '-- Choose one --']) !!}
</div>


<!-- Product Brand Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('is_active', 'Is Active:') !!}
    {!! Form::select('is_active', [
        1 => 'Yes',
        0 => 'No',
    ], null, ['class' => 'form-control', 'required' => true, 'placeholder' => '-- Choose one --']) !!}
</div>
<!-- Product Brand Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('is_stock_available', 'Is Stock Available:') !!}
    {!! Form::select('is_stock_available', [
        1 => 'Yes',
        0 => 'No',
    ], null, ['class' => 'form-control', 'required' => true, 'placeholder' => '-- Choose one --']) !!}
</div>



<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => ' cropit-trigger btn btn-primary']) !!}
    <a href="{!! route('products.index') !!}" class="btn btn-default">Cancel</a>
</div>
