@extends('frontend.index')
@section('title', 'Review')

@section('content')

<div class="container my-4">
    <div class="row">
        <div class="col-md-12">

            <!-- ALERT MESSAGES -->
            @include('alert')

            <form id="reviewForm" name="reviewForm" action="{{ route('reviews.filter') }}" method="post">
                @csrf
                <div class="input-group sdginfocus align-items-end">
                    @if(in_array(Session::get('userData')->permission_id, [1]) && in_array(Session::get('userData')->roles_name, ['Super Admin','Admin']))
                    <div>
                        <label class="input-group-text p-0 pr-3" for="inputGroupSelect01">Select Division/Department</label>
                        <select class="form-select mr-2 p-1" id="sdg_division_id" name="sdg_division_id" aria-label="Select Division/Department with button addon and a label.">
                            
                            @if(!$divisions->isEmpty())
                                @foreach ($divisions as $key => $division)
                                    <option value="{{ $division->id }}" {{ ( $selectedDivision == $division->id) ? 'selected' : Session::get('userData')->divisions_label }}> 
                                        {{ $division->label }}
                                    </option>
                                @endforeach 
                            @endif
                        </select>
                    </div>
                    @endif
                    <div>
                        <label class="input-group-text p-0" for="inputGroupSelect01">Select SDG in Focus</label>
                        <select class="form-select mr-2 p-1" id="sdg_topic_id" name="sdg_topic_id" aria-label="Select SDG Topic with button addon and a label.">
                            
                            <option selected value="">View all</option>
                            
                            @if(!$sdgtopics->isEmpty())
                                @foreach ($sdgtopics as $key => $sdgtopic)
                                    <option value="{{ $sdgtopic->id }}" {{ ( $selectedSDGTopic == $sdgtopic->id) ? 'selected' : '' }}> 
                                        {{ $sdgtopic->label }}
                                    </option>
                                @endforeach 
                            @endif
                        </select>
                    </div>
                    <button class="btn btn-primary rounded-0" id="btn-save" type="submit">FILTER</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <hr/>
            
            @if(in_array(Session::get('userData')->permission_id, [1]) && in_array(Session::get('userData')->roles_name, ['Super Admin','Admin']))
                <form id="newSubmissionForm" name="newSubmissionForm" action="{{ route('reviews.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <table class="table table-borderless" id="champion_datatable">
                        <thead>
                            <tr class="bg-info text-light">
                                <th scope="col">KCB SDG Metric / KPI</th>
                                <th scope="col">Division</th>
                                <th scope="col">Target by <span class="review_cycle"></span></th>
                                <th scope="col">Last submission</th>
                                <th scope="col">Percentage</th>
                                @if(in_array(Session::get('userData')->permission_id, [1]) && in_array(Session::get('userData')->roles_name, ['Super Admin','Admin']))
                                    <th scope="col">Add New Submission</th>
                                @endif
                                <th scope="col">Action</th>
                            </tr>
                            <tbody>
                                @if(!empty($kpis))
                                    @if(!$kpis->isEmpty())
                                        @foreach(json_decode($kpis) as $kpis)
                                            @foreach($kpis as $key => $kpi)
                                                @if($key < 1)
                                                    <tr colspan="7">
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="7" class="text-uppercase font-weight-bold bg-secondary text-light line-height-normal border border-top-0 border-left-0 border-right-0 border-info  py-1">
                                                            {{$kpi->sdgtopics_label}}
                                                        </td>
                                                    </tr>
                                                @endif
                                                
                                                @if($kpi->status === 0)
                                                    @php $highlight = 'bg-success-status'; @endphp
                                                @elseif($kpi->status === 1 AND $kpi->sub_total !== 0 AND $kpi->sub_total !== NULL)
                                                    @php $highlight = 'bg-danger-status'; @endphp
                                                @else
                                                    @php $highlight = 'bg-dark-status'; @endphp
                                                @endif
                                                <tr class="{{$highlight}}">
                                                    <td>{{$kpi->label}}</td>
                                                    <td>{{$kpi->division_name}}</td>
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
                                                        @if(in_array(Session::get('userData')->permission_id, ['1']) && in_array(Session::get('userData')->roles_name, ['Super Admin','Admin']))
                                                            <td>   
                                                                <input type="hidden" name="kpi_id[{{$kpi->id}}]" id="kpi_id[{{$kpi->id}}]" value="{{$kpi->id}}">
                                                                <input type="hidden" name="target[{{$kpi->id}}]" id="target[{{$kpi->id}}]" value="{{$kpi->target}}">
                                                                <input type="hidden" name="sdg_topic_id[{{$kpi->id}}]" id="sdg_topic_id[{{$kpi->id}}]" value="{{$kpi->sdg_topic_id}}">
                                                                <input type="hidden" name="division_id[{{$kpi->id}}]" id="division_id[{{$kpi->id}}]" value="{{$kpi->division_id}}">
                                                                
                                                                @if($kpi->status === null || $kpi->status)
                                                                    <input type="number" name="sub_total[{{$kpi->id}}]" id="sub_total[{{$kpi->id}}]" value="{{ old('sub_total') ?? $kpi->sub_total ?? 0 }}" min="0" class="w-100 py-0"><!-- Add New Submission -->
                                                                @else
                                                                    <input type="hidden" name="sub_total[{{$kpi->id}}]" id="sub_total[{{$kpi->id}}]" value="{{ old('sub_total') ?? $kpi->sub_total ?? 0 }}">
                                                                    <input type="number" name="" id="sub_total[{{$kpi->id}}]" value="{{ old('sub_total') ?? $kpi->sub_total ?? 0 }}" min="0" disabled class="w-100 py-0"><!-- Add New Submission -->
                                                                @endif
                                                                
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
                                                        @endif
                                                    <td>
                                                        <span>
                                                            @if($kpi->sub_total != 0)
                                                                    @if($kpi->status)
                                                                        <a href="{{ route('reviews.status', ['id' => $kpi->id, 'status' => 0]) }}" class="text text-danger"><i class="fas fa-lock-open"></i> Not Approved</a>
                                                                    @else
                                                                        <a href="{{ route('reviews.status', ['id' => $kpi->id, 'status' => 1]) }}" class="text text-success"><i class="fas fa-lock"></i> Approved</a>
                                                                    @endif
                                                                @else
                                                                    <span class="badge bg-dark text-light">No submissions</span>
                                                            @endif
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach

                                        @if(in_array(Session::get('userData')->permission_id, [1]) && in_array(Session::get('userData')->roles_name, ['Super Admin','Admin']))
                                            <tr>
                                                <td colspan="7" class="pt-0 px-0">
                                                    <hr/>
                                                    <button class="btn btn-primary float-right" id="btn-save" type="submit">Submit</button>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        <div class="alert alert-warning text-center">Empty filter results!</div>
                                    @endif
                                @endif
                            </tbody>
                        </thead>
                    </table>
                </form>
            @else
                <form id="newSubmissionForm" name="newSubmissionForm" action="{{ route('reviews.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <table class="table table-borderless" id="review_datatable">
                        <thead>
                            <tr class="bg-secondary text-light">
                                <th scope="col">KCB SDG Metric / KPI</th>
                                <th scope="col">Target by <span class="review_cycle"></span></th>
                                <th scope="col">Last submission</th>
                                <th scope="col">Add New Submission</th>
                            </tr>
                            <tr colspan="4"><td></td></tr>
                        </thead>
                        <tbody>
                            @if(!empty($kpis))
                                @if(!$kpis->isEmpty())
                                    @foreach(json_decode($kpis) as $kpis)
                                        @foreach($kpis as $key => $kpi)
                                            @if($key < 1)
                                                <tr>
                                                    <td colspan="4" class="bg-light border border-top-0 border-left-0 border-right-0 border-info  py-1">{{$kpi->sdgtopics_label}}</td>
                                                </tr>
                                            @endif
                                            @if(in_array(Session::get('userData')->division_id, json_decode($kpi->division_id)))
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
                                                    <td>   
                                                        <input type="hidden" name="kpi_id[{{$kpi->id}}]" id="kpi_id[{{$kpi->id}}]" value="{{$kpi->id}}">
                                                        <input type="hidden" name="target[{{$kpi->id}}]" id="target[{{$kpi->id}}]" value="{{$kpi->target}}">
                                                        <input type="hidden" name="sdg_topic_id[{{$kpi->id}}]" id="sdg_topic_id[{{$kpi->id}}]" value="{{$kpi->sdg_topic_id}}">
                                                        <input type="hidden" name="division_id[{{$kpi->id}}]" id="division_id[{{$kpi->id}}]" value="{{$kpi->division_id}}">
                                                        @if($kpi->status === null || $kpi->status)
                                                            <input type="number" name="sub_total[{{$kpi->id}}]" id="sub_total[{{$kpi->id}}]" value="{{ old('sub_total') ?? $kpi->sub_total ?? 0 }}" min="0" class="w-100 py-0"><!-- Add New Submission -->
                                                        @else
                                                            <input type="hidden" name="sub_total[{{$kpi->id}}]" id="sub_total[{{$kpi->id}}]" value="{{ old('sub_total') ?? $kpi->sub_total ?? 0 }}">
                                                            <input type="number" name="" id="sub_total[{{$kpi->id}}]" value="{{ old('sub_total') ?? $kpi->sub_total ?? 0 }}" min="0" disabled class="w-100 py-0"><!-- Add New Submission -->
                                                        @endif
                                                        <!-- <input type="number" name="sub_total[{{$kpi->id}}]" id="sub_total[{{$kpi->id}}]" value="{{ old('sub_total') ?? $kpi->sub_total ?? 0 }}" min="0" class="w-100 py-0"> Add New Submission -->

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
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td colspan="4">
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
            @endif

        </div>
    </div>
</div>

@endsection