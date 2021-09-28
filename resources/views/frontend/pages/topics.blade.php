@extends('frontend.index')
@section('title', $division->label)

@section('content')

<div class="container-fluid my-4">
    <div class="row">
        <div class="col-md-8">
            <h3 class="mb-0">{{$division->label}} SDG Overview</h3>
        </div>
        <div class="col-lg-4">
            <form id="filterByYear"  enctype="multipart/form-data">
                    <input id="division_id" type="hidden" name="divisionid" value="{{$division->id}}">
                    <div class="form-group">
                        <select id="selectyear" name="selectyear" class="form-control chooseyear" >
                            <option selected disabled>Filter by year </option>
                            @foreach ($reviewYears as $item)
                               <option value="{{$item->year}}">{{$item->year}}</option>
                            @endforeach
                        </select>
                    </div>
            </form>
        </div>
        <div class="col-lg-12">
          <hr/>
        </div>
    </div>

    <div class="row mx-auto justify-content-center align-items-center w-75">
         @if($chartGoals or $chartjsBarGoals)
            <div class="col-md-4">
                @foreach($chartGoals as $chart)
                    {!! $chart->render() !!}
                    <div class="mt-5"><hr/><hr/></div>
                @endforeach
            </div>
            <div class="col-md-8">
                @foreach($chartjsBarGoals as $chartBar)
                    {!! $chartBar->render() !!}
                    <div class="mt-5">&nbsp;</div>
                @endforeach
            </div>
        @else
            <div class="col-lg-12">
                <i>Oops Sorry! No data found</i>
            </div>
        @endif

    </div>
</div>


@endsection

@push('scripts')
<script>
    jQuery(document).ready(function($) {

          $('#selectyear').on('change', function(e) {
              e.preventDefault();
              var year = $('#selectyear').val();
              $.ajax({
                    type:'GET',
                    url: "{{$division->label}}/"+{{$division->id}}, //SITEURL +
                    data: {
                        'year': year,
                        'id': {{$division->id}}
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    success: (data) => {
                        console.log(data.message);
                        if(data.status == 200){
                            location.reload();
                        }
                    },
                    error: function(error){
                        console.log(error);

                    }
                });

        });
    });
</script>
@endpush
