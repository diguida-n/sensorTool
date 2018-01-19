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
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="box-title">{{ trans('backpack::base.login_status') }}</div>
                </div>

                <div class="box-body">{{ trans('backpack::base.logged_in') }}</div>
            </div>
        </div>
    </div>
@endsection