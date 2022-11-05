@extends('blank')

@section('after_styles')
    <style media="screen">
        .backpack-profile-form .required::after {
            content: ' *';
            color: red;
        }
    </style>
@endsection

@php
  $breadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      trans('backpack::base.my_account') => false,
  ];
@endphp

@section('header')
    <section class="content-header">
        <div class="container-fluid mb-3">
            <h1>{{ $stories->title }}</h1>
        </div>
    </section>
@endsection

@section('content')
            <h5>{{ $stories->published_date }}</h5>
            <p>{!! $stories->content !!}</p>
@endsection