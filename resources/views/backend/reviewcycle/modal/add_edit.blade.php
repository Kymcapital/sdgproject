<div class="modal fade" id="cycleReviewModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="cycleReviewTitle"></h6>
            </div>

            <div class="modal-body">

                <!-- ALERT MESSAGES -->
                @include('alert')
                <form id="cycleForm" name="cycleForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="review_cycle_id" id="review_cycle_id">

                    <div class="col">
                        <div class="form-group">
                            <label for="label" class="control-label"> Review Cycle </label>
                            <input type="text" class="form-control" id="label" name="label" placeholder="Enter Review Cycle" value="">
                            <div class="text-danger" id="labelError"></div>
                        </div>
                    </div>

                    <div class="col">
                        <input type="hidden" class="form-control" id="year" name="year" value="">

                        <div class="form-group">
                            <label for="">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                            <div class="text-danger" id="start_dateError"></div>
                        </div>
                    </div>

                    <div class="col">
                        <label for=""> End Date</label>
                        <div class="form-group">
                            <input type="date" class="form-control" id="end_date" name="end_date">
                            <div class="text-danger" id="end_dateError"></div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group text-right  mt-2">
                           <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save</button>
                       </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer"></div>

        </div>
    </div>
</div>
