@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Product Unit Model
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($productUnitModel, ['route' => ['productUnitModels.update', $productUnitModel->id], 'method' => 'patch']) !!}

                        @include('product_unit_models.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection