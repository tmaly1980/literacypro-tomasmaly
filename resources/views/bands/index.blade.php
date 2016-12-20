@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Bands
            <a class="btn btn-success pull-right" href="{{ route('bands.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($bands->count())
                <table class="table table-condensed ">
                    <thead>
                        <tr>
                            <th>@sortablelink('id','ID')</th>
                            <th>@sortablelink('name','NAME')</th>
                            <th>@sortablelink('start_date','START_DATE')</th>
                            <th>@sortablelink('website','WEBSITE')</th>
                            <th>@sortablelink('still_active','Status')</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($bands as $band)
                    <tr  class='{{ $band->still_active ? 'bg-success' : 'bg-warning' }}'>
                        <td>{{$band->id}}</td>
                        <td>
                            {!! link_to_route('bands.show', $band->name, [$band->id]) !!}
                        </td>
                        <td>{{$band->start_date}}</td>
                        <td>{!! link_to($band->website) !!}</td>
                        <td>
                            @if($band->still_active)
                                @fa('smile-o') ACTIVE
                            @else
                                @fa('frown-o') INACTIVE
                            @endif 
                        </td>
                                <td class="text-right">
                                   
                                    <a class="btn btn-xs btn-warning" href="{{ route('bands.edit', $band->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('bands.destroy', $band->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $bands->appends(\Request::except('page'))->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>

@endsection