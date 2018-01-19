@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Admin <small>Aggiungi responsabile aziendale</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li><a href="{{ backpack_url() }}/enterprise" class="text-capitalize">Imprese</a></li>
        <li class="active">Aggiungi Responsabile Aziendale</li>
      </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <!-- Default box -->
            @if ($crud->hasAccess('list'))
                <a href="{{ url($crud->route) }}"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br>
            @endif

            @include('crud::inc.grouped_errors')

              {!! Form::open(array('url' => route('admin.enterprise.storeCompanyManager',$enterpriseId), 'method' => 'post')) !!}
              <div class="box">

                <div class="box-header with-border">
                  <h3 class="box-title">{{ trans('backpack::crud.add_a_new') }} Responsabile aziendale</h3>
                </div>
                <div class="box-body row">
                    @include('crud::form_content', [ 'fields' => $fields, 'action' => 'create' ])
                </div><!-- /.box-body -->
                <div class="box-footer">

                    @include('crud::inc.form_save_buttons')

                </div><!-- /.box-footer-->

              </div><!-- /.box -->
              {!! Form::close() !!}
        </div>
    </div>
@endsection