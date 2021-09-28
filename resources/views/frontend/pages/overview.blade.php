@extends('frontend.index')
@section('title', 'Overview')

@section('content')

<div class="gray-background p-5 align-items-center remove-mmargin-x-axis">
    <div class="container">
        <div class="row align-items-center mx-auto">
            <div class="col-md-4">
                <h1 class="text-uppercase big-header">Sustainable <br/>Development</h1>
                <img src="{{asset('images/goal.png')}}" class="img-fluid logo mt-1" alt="Sustainable Development Goals">
            </div>
            <div class="col-md-8">
                <h3>KCB Group SDG reporting dashboard</h3>

                <h5>SDG's adopted across the group</h5>
                <p>
                    <ol>
                    <li>SDG1 No Poverty.</li> <li>SDG 8 Decent Work and economic growth.</li>
                    <li>SDG 9 Industry Innovation and Infrastructure.</li> <li>SDG10 Reduced Inequalities.</li>
                    <li>SDG 11Sustainable Cities and communities.</li> <li>SDG 12 ResponsibleConsumption and Production.</li>
                    <li>SDG 13 Climate action.</li><li>SDG 16 Peace, Justice and Strong Institutions.</li>
                    <li>SDG 17 Partnerships for the Goals.</li>
                    </ol>
                </p>
                <h5>SDG Vision Statement</h5>
                <p>Through sound systems and partnerships, KCB Group is re-imagining societal values, this vision includes a world free from poverty and inequality, resulting from employment and growth opportunities, which arise from enhanced innovation and infrastructure.  We see a future with cities that are thriving sustainably and adhering to the principles of responsible consumption and production.</p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="my-5">
        {{-- KCB SDG Overview --}}
        <div class="row">
            <div class="col-lg-8">
                <h2 class="text-uppercase my-4">KCB SDG Overview</h2>
            </div>
            <div class="col-lg-4 mt-4">
                <form id="filterYearly"  enctype="multipart/form-data">
                    <div class="form-group">
                        <select id="filterbyyear" name="year" class="form-control chooseyear" >
                            <option selected disabled>Filter by year </option>
                            @foreach ($reviewYears as $item)
                               <option value="{{$item->year}}">{{$item->year}}</option>
                            @endforeach
                        </select>
                    </div>
            </form>
            </div>
        </div>

        <!-- Chart's container -->
        @php $count=0; @endphp
        @if($chartGoals or $chartGoals)
                @foreach($chartGoals as $chart)
                    @if($count==0 || $count%4==0 )
                    <div class="row">
                    @endif

                    <div class="col-md-3 mb-5">
                        <div class="chartjsRender">
                            {!! $chart->render() !!}
                        </div>
                    </div>

                    @php $count+=1;@endphp
                        @if($count==0 || $count%4==0 || $count==sizeof($chartGoals) )
                        </div>
                        @endif
                @endforeach
        @else
         <div class="row">
             <div class="col-lg-12">
                <i>Oops Sorry! No data found</i>
             </div>
         </div>
        @endif

    </div>
</div>

@endsection
@push('scripts')
    <script>
        jQuery(document).ready(function($) {
            $('#filterYearly').on('change', function(e) {
                e.preventDefault();
                var year = $('#filterbyyear').val();
                $.ajax({
                        type:'GET',
                        url: "/overview", //SITEURL +
                        data: {
                            'year': year,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        success: (data) => {
                            console.log(data);
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
@endpush('scripts')
