@extends('layout')
@section('header')
<div class="page-header">
        <h1>Albums / Show #{{$album->id}}</h1>
        <form action="{{ route('albums.destroy', $album->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('albums.edit', $album->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <button type="submit" class="btn btn-danger">Delete <i class="glyphicon glyphicon-trash"></i></button>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <form action="#">
                <div class="form-group">
                    <label for="nome">ID</label>
                    <p class="form-control-static"></p>
                </div>
                <div class="form-group">
                     <label for="band_id">BAND_ID</label>
                     <p class="form-control-static">{{$album->band_id}}</p>
                </div>
                    <div class="form-group">
                     <label for="name">NAME</label>
                     <p class="form-control-static">{{$album->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="recorded_date">RECORDED_DATE</label>
                     <p class="form-control-static">{{$album->recorded_date}}</p>
                </div>
                    <div class="form-group">
                     <label for="release_date">RELEASE_DATE</label>
                     <p class="form-control-static">{{$album->release_date}}</p>
                </div>
                    <div class="form-group">
                     <label for="number_of_tracks">NUMBER_OF_TRACKS</label>
                     <p class="form-control-static">{{$album->number_of_tracks}}</p>
                </div>
                    <div class="form-group">
                     <label for="label">LABEL</label>
                     <p class="form-control-static">{{$album->label}}</p>
                </div>
                    <div class="form-group">
                     <label for="producer">PRODUCER</label>
                     <p class="form-control-static">{{$album->producer}}</p>
                </div>
                    <div class="form-group">
                     <label for="genre">GENRE</label>
                     <p class="form-control-static">{{$album->genre}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('albums.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection