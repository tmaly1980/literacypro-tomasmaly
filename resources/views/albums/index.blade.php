@extends('layout')

@section('header')

@endsection

@section('content')
	{!! BootForm::select('band_id','Band',App\Band::pluck('name','id'), request('band_id'), ['placeholder'=>'- All Bands -','onChange'=>"window.location = '/albums?band_id='+this.value"] ) !!}

    @include('albums/list')



@endsection