@extends('layout')
@section('header')
<div class="page-header">
        <h1 class=''>
        {!! $band->name !!}
                            @if($band->still_active)
                                <span class='btn btn-success'>@fa('smile-o') ACTIVE</span>
                            @else
                                <span class='btn btn-warning'>@fa('frown-o') INACTIVE</span>
                            @endif 
        </h1>
        <form action="{{ route('bands.destroy', $band->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('bands.edit', $band->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="name">NAME</label>
                     <p class="form-control-static">{{$band->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="start_date">START_DATE</label>
                     <p class="form-control-static">{{$band->start_date}}</p>
                </div>
                    <div class="form-group">
                     <label for="website">WEBSITE</label>
                     <p class="form-control-static">{!! link_to($band->website) !!}</p>
                </div>
            </form>

            @include('albums/list',['band_id'=>$band->id,'albums'=>$band->albums()->sortable(['name'=>'asc'])->paginate(10)])

            <a class="btn btn-link" href="{{ route('bands.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection