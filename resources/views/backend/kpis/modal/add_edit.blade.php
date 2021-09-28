<div class="modal fade" id="ajax-kpi-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="kpiCrudModal"></h6>
            </div>

            <div class="modal-body">

                <!-- ALERT MESSAGES -->
                @include('alert')

                <form id="kpiForm" name="kpiForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="kpi_id" id="kpi_id">

                    <div class="form-group">
                        <label for="label" class="control-label">SDG Metric / KPI </label>
                        <input type="text" class="form-control" id="label" name="label" placeholder="Enter KPI" value="">
                        <div class="text-danger" id="labelError"></div>
                    </div>

                    <div class="row form-group">
                        <div class="col">
                            <div class="form-group">
                                <select class="form-control" name="cycle_id" id="cycle_id">
                                    <option selected value="">Select Cycle</option>
                                    @foreach ($cycles as $key => $cycle)
                                        <option value="{{ $cycle->id }}" {{ ( $key == $cycle->id) ? 'selected' : '' }}>
                                            {{ $cycle->label }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="text-danger" id="cycleError"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input type="number" class="form-control" id="target" name="target" placeholder="Enter Target" min="0" value="">
                                <div class="text-danger" id="targetError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col">
                            <div class="form-group">
                                <select class="form-control" name="sdg_topic_id" id="sdg_topic_id">
                                    <option selected value="">Select SDG Topic</option>
                                    @foreach ($sdgtopics as $key => $sdgtopic)
                                        <option value="{{ $sdgtopic->id }}" {{ ( $key == $sdgtopic->id) ? 'selected' : '' }}>
                                            {{ $sdgtopic->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <select class="form-control" name="division_id" id="division_id">
                                    <option selected value="">Select Division</option>
                                    @foreach ($divisions as $key => $division)
                                        <option value="{{ $division->id }}" {{ ( $key == $division->id) ? 'selected' : '' }}>
                                            {{ $division->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col">
                            <div class="form-group">
                                <select class="form-control" name="company_id" id="company_id" hidden>
                                    <option selected value="">Select Company</option>
                                    @foreach ($companies as $key => $company)
                                        <option value="{{ $company->id }}" {{ ( $key == $company->id) ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save</button>
                </form>
            </div>

            <div class="modal-footer"></div>

        </div>
    </div>
</div>
