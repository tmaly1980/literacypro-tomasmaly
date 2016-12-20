<? 
$band_id = Request::input("band_id");
?>
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> {!! !empty($album) ? "Edit Album #{$album->id}" : "Add Album" !!}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">
          {!! BootForm::open(['model'=>!empty($album)?$album:null,'store'=>'albums.store','update'=>'albums.update']) !!}

            {!! Form::token() !!}

            {!! BootForm::select('band_id', "Band", App\Band::pluck('name','id'), !empty($band_id)?$band_id:null) !!}

            {!! BootForm::text('name', null, null,['required'=>1]) !!}
            {!! BootForm::text('recorded_date',"Recorded",null,['class'=>'date-picker']) !!}
            {!! BootForm::text('release_date',"Release Date",null,['class'=>'date-picker']) !!}
            {!! BootForm::number('number_of_tracks',null,1, ['min'=>1,'step'=>1]) !!}

            {!! BootForm::text('label') !!}
            {!! BootForm::text('producer') !!}
            {!! BootForm::select('genre', null, App\Album::genreList()) !!}

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('albums.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
                </div>
            {!! BootForm::close() !!}
        </div>
    </div>
@endsection
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
  <script>
    $('.date-picker').datepicker({
      autoclose: true
    });
  </script>
@endsection
