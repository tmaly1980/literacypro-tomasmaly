    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Albums
            <a class="btn btn-success pull-right" href="{{ route( 'albums.create') }}?band_id={!! !empty($band_id) ? $band_id : null !!}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
    

    <div class="row">
        <div class="col-md-12">
            @if($albums->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>@sortablelink('id','ID')</th>
                            <th>@sortablelink('band_id','BAND')</th>
                        <th>@sortablelink('name','NAME')</th>
                        <th>@sortablelink('recorded_date','Recorded')</th>
                        <th>@sortablelink('release_date','Released')</th>
                        <th>@sortablelink('number_of_tracks','# Tracks')</th>
                        <th>@sortablelink('label','LABEL')</th>
                        <th>@sortablelink('producer','PRODUCER')</th>
                        <th>@sortablelink('genre','GENRE')</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($albums as $album)
                            <tr>
                                <td>{{$album->id}}</td>
                                <td>{!! link_to_route('bands.show', $album->band->name, [$album->band_id]) !!}</td>
                    <td>{!! link_to_route('albums.show', $album->name, [$album->id]) !!}</td>
                    <td>{{$album->recorded_date}}</td>
                    <td>{{$album->release_date}}</td>
                    <td>{{$album->number_of_tracks}}</td>
                    <td>{{$album->label}}</td>
                    <td>{{$album->producer}}</td>
                    <td>{{$album->genre}}</td>
                                <td class="text-right">
                                <nobr>
                                    <a class="btn btn-xs btn-warning" href="{{ route('albums.edit', $album->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('albums.destroy', $album->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                    </nobr>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $albums->appends(\Request::except('page'))->render() !!}
            @else
                <h3 class="text-center alert alert-info">No albums!</h3>
            @endif

        </div>
    </div>