@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i>  {!! !empty($band) ? "Edit Band #{$band->id}" : "Add Band" !!}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

        {!! BootForm::open(['model'=>!empty($band)?$band:null,'store'=>'bands.store','update'=>'bands.update']) !!}

            {!! Form::token() !!}

            {!! BootForm::text('name', null, null,['required'=>1]) !!}
            {!! BootForm::text('start_date',"Start Date",null,['class'=>'date-picker']) !!}
            {!! BootForm::text('website',"Website") !!}
            <script>
            $('#website').change(function() {
              if(!$(this).val().match(/^\w+:\/\//))
              {
                $(this).val("http://"+$(this).val());
              }
            });
            </script>
            {!! BootForm::select('still_active',"Status",['Inactive','Active'],1) !!}

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('bands.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
                </div>
            {!! BootForm::close() !!}

            @if(!empty($band->id))
              @include('bands/list',['band_id'=>$band->id,'bands'=>$band->bands()->sortable(['name'=>'asc'])->paginate(10)])
            @endif

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
