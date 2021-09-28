@extends('layout')
@section('title', 'Champions')

@section('activeAddBtn', '')
@section('activeImportBtn', '')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <form id="championForm" name="championForm" action="{{ route('champions.filter') }}" method="post">
                @csrf
                <div class="input-group sdginfocus">
                    <label class="input-group-text" for="inputGroupSelect01">Select SDG in Focus</label>
                    <select class="form-select mr-2" id="sdg_topic_id" name="sdg_topic_id" aria-label="Select SDG Topic with button addon and a label.">
                        
                        <option selected value="">View all</option>
                        
                        @if(!$sdgtopics->isEmpty())
                            @foreach ($sdgtopics as $key => $sdgtopic)
                                <option value="{{ $sdgtopic->id }}" {{ ( $key == $sdgtopic->id) ? 'selected' : '' }}> 
                                    {{ $sdgtopic->label }}
                                </option>
                            @endforeach 
                        @endif
                    </select>
                    <button class="btn btn-primary" id="btn-save" type="submit">Go</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <hr/>

            <form id="newSubmissionForm" name="newSubmissionForm" action="{{ route('champions.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <table class="table table-borderless" id="champion_datatable">
                    <thead>
                        <tr class="bg-secondary text-light">
                            <th scope="col">KCB SDG Metric / KPI</th>
                            <th scope="col">Target by <span class="review_cycle"></span></th>
                            <th scope="col">Last submission</th>
                            <th scope="col">Percentage</th>
                            <th scope="col">Add New Submission</th>
                        </tr>
                        <tr colspan="5"><td></td></tr>
                    </thead>
                    <tbody>
                        @if(!empty($kpis))
                            @if(!$kpis->isEmpty())
                                @foreach(json_decode($kpis) as $kpis)
                                    @foreach($kpis as $key => $kpi)
                                        @if($key < 1)
                                            <tr>
                                                <td colspan="5" class="bg-light border border-top-0 border-left-0 border-right-0 border-info  py-1">{{$kpi->sdgtopics_label}}</td>
                                                
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>{{$kpi->label}}</td>
                                            <td>{{number_format($kpi->target)}}</td>
                                            <td>
                                                @if($kpi->sub_total !== 0 AND $kpi->sub_total !== NULL)
                                                    <span class="badge bg-info text-light p-2 line-height-normal">
                                                        {{number_format($kpi->sub_total)}}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-dark p-2 line-height-normal">
                                                        {{number_format($kpi->sub_total)}}
                                                    </span>
                                                @endif
                                            </td> <!-- Last submission -->
                                            <td>{{number_format($kpi->total)}}%</td>
                                            <td>   
                                                <input type="hidden" name="kpi_id[{{$kpi->id}}]" id="kpi_id[{{$kpi->id}}]" value="{{$kpi->id}}">
                                                <input type="hidden" name="target[{{$kpi->id}}]" id="target[{{$kpi->id}}]" value="{{$kpi->target}}">
                                                <input type="hidden" name="sdg_topic_id[{{$kpi->id}}]" id="sdg_topic_id[{{$kpi->id}}]" value="{{$kpi->sdg_topic_id}}">
                                                <input type="hidden" name="division_id[{{$kpi->id}}]" id="division_id[{{$kpi->id}}]" value="{{$kpi->division_id}}">
                                                <input type="number" name="sub_total[{{$kpi->id}}]" id="sub_total[{{$kpi->id}}]" value="{{ old('sub_total') ?? $kpi->sub_total ?? 0 }}" min="0" class="w-100 py-0"><!-- Add New Submission -->
                                                
                                                <a href="javascript:void(0)" class="" data-toggle="modal" data-target="#submissionModal{{$kpi->id}}">
                                                    view submision history
                                                </a>

                                                <!-- Modal -->
                                                <div class="modal fade" id="submissionModal{{$kpi->id}}" tabindex="-1" role="dialog" aria-labelledby="submissionModal{{$kpi->id}}Title" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">{{$kpi->label}}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-striped" id="">
                                                                    <thead>
                                                                        <th class="bg-secondary text-light">Last Submissions</th>
                                                                        <th class="bg-secondary text-light">By</th>
                                                                        <th class="bg-secondary text-light">Date/Time</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($submissions as $values)
                                                                            @foreach($values as $submission)
                                                                                @if($submission->kpi_id == $kpi->id)
                                                                                <tr>
                                                                                    <td>{{$submission->last_submission}}</td>
                                                                                    <td>{{$submission->first_name}}</td>
                                                                                    <td>{{\Carbon\Carbon::parse($submission->created_at)->format('Y M d / H:i:s')}}</td>
                                                                                </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td colspan="5">
                                        <button class="btn btn-primary float-right" id="btn-save" type="submit">Submit</button>
                                    </td>
                                </tr>
                            @else
                                <div class="alert alert-warning text-center">Empty filter results!</div>
                            @endif
                        @endif
                    </tbody>
                </table>
            </form>

        </div>
    </div>

@endsection