<!-- Title Field -->
<div class="form-group col-sm-12">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control', 'maxlength' => 50, 'required' => true]) !!}
</div>

<!-- News Category Id Field -->
<div class="form-group col-sm-12">
    {!! Form::label('news_category_id', 'News Category:') !!}
    {!! Form::select('news_category_id', $news_categories, null, ['class' => 'form-control', 'required' => true, 'placeholder' => '-- Choose one --']) !!}
</div>

<!-- Photo Field -->
<div class="form-group col-sm-12" align="center">
    {!! Form::label('photo', 'Photo:') !!}
    <!--{!! Form::file('photo', null, ['class' => 'form-control']) !!}-->
    <!-- This wraps the whole cropper -->
    <div id="image-cropper">
    <!-- This is where the preview image is displayed -->
        <div class="cropit-preview"></div>

        <!-- This range input controls zoom -->
        <!-- You can add additional elements here, e.g. the image icons -->
        <br>
        <input type="range" class="cropit-image-zoom-input" />
        <br>
        <!-- This is where user selects new image -->
        <input type="file" class="cropit-image-input" />

        @if (isset($news))
            <input class="cropit-target hidden" name="photo" value="{{ asset('storage/news/' . $news->photo) }}" />
        @else
            <input class="cropit-target hidden" name="photo" />
        @endif
        <!-- The cropit- classes above are needed
            so cropit can identify these elements -->
    </div>
</div>

<!-- Content Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('content', 'Content:') !!}
    {!! Form::textarea('content', null, ['class' => 'form-control rich-text']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary cropit-trigger']) !!}
    <a href="{!! route('news.index') !!}" class="btn  btn-default">Cancel</a>
</div>
