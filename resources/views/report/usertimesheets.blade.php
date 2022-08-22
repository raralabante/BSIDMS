@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content">
        <div class="container-fluid" style="overflow: auto">
            <div class="row border border-1 rounded-3 btn-dark-green filters">
                <center>
                    <div class="input-group m-2" style="width:30%">
                        <span class="input-group-text btn-dark-green text-white">FROM</span>
                        <input id="from" type="date" class="form-control" required>
                        <span class="input-group-text btn-dark-green text-white">TO</span>
                        <input id="to" type="date" class="form-control" required>
                        <div class="invalid-feedback">
                            <i class="fa-solid fa-triangle-exclamation"></i> Date range cannot be empty.
                        </div>
                    </div>
                    <div class="input-group p-2" style="width: 80%">
                        <span class="input-group-text">Department</span>
                        <select class="form-select form-select-sm input-group-sm" id="department">
                            @if ($department == 'drafting')
                                {
                                <option value="">Select Department</option>
                                <option value="DFT" selected>Drafting</option>
                                <option value="SCHEDES">Scheduling</option>
                                }
                            @elseif($department == 'scheduling')
                                {
                                <option value="">Select Department</option>
                                <option value="DFT">Drafting</option>
                                <option value="SCHEDES" selected>Scheduling</option>
                                }
                            @endif

                        </select>
                        <span class="input-group-text">TEAM</span>
                        <select class="form-select form-select-sm input-group-sm" id="team">
                            @foreach ($teams as $team)
                                <option value="{{ $team->code_value }}" data-department="{{ $team->desc1 }}">
                                    {{ $team->code_value }}</option>
                            @endforeach
                        </select>
                        <span class="input-group-text">USER</span>
                        <select class="form-select form-select-sm input-group-sm" id="user">
                            <option value="">Select User</option>
                        </select>

                    </div>


                    <div class=" m-2">
                        <button id="generate_btn" class="btn-dark-green  btn text-white"><i
                                class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;GENERATE</button>
                        <button id="clear_filter_btn" class="btn-dark-green  btn text-white"><i
                                class="fa-solid fa-filter-circle-xmark"></i>&nbsp;&nbsp;CLEAR FILTERS</button>
                    </div>
            </div>

            </center>
            <br>
            <div class="">
                <table id="usertimesheets_tbl" class="table table-bordered row-border order-column stripe hover"
                    data-mode="columntoggle"width="100%">
                    <tfoot>
                        <tr>
                            <th colspan="5" style="text-align:right">Total Hours:</th>
                            <th id="total"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>

    </div>
@endsection
</div>
<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
<script>
    $(document).ready(function() {
        $(".usertimesheets").addClass("sidebar_active");
        $("#reportsSubmenu").click();
        const toastLiveExample = document.getElementById('liveToast');
        const toast = new bootstrap.Toast(toastLiveExample);
        const warningToast = document.getElementById('warningToast');
        const toastWarning = new bootstrap.Toast(warningToast);

        $("#from").val(moment().format('YYYY-MM-DD'));
        $("#to").val(moment().format('YYYY-MM-DD'));

        $("#department").change(function() {
            $("#team option").hide();
            $("[data-department='" + $(this).val() + "']").show();
            $("[data-department='" + $(this).val() + "']").eq(0).prop('selected', true).parent()
                .change();

        }).change();

        $("#team").change(function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: "{{ route('user.getUsersByTeam') }}",
                data: {
                    department: $("#department").val(),
                    team: $(this).val()
                },
                success: function(data) {
                    var user = $("#user");
                    user.empty();
                    user.append($("<option value='' selected />").text("Select User"));
                    $.each(data, function(i, item) {
                        user.append($("<option />").val(data[i].value).text(data[i]
                            .label));
                    });
                }
            });
        }).change();

        $("#generate_btn").click(function() {


            if ($("#from").val() == "" || $("#to").val() == "") {
                $(".invalid-feedback").show();
                $("#warningToast .toast-body").html(
                    "<i class='fa-solid fa-ban'></i> Date range cannot be empty.");
                toastWarning.show();
                $("#from").focus();
            } else {

                if ($.fn.DataTable.isDataTable('#usertimesheets_tbl')) {
                    $('#usertimesheets_tbl').dataTable().fnClearTable();
                    $('#usertimesheets_tbl').dataTable().fnDestroy();
                }
                var usertimesheets_tbl = $('#usertimesheets_tbl').DataTable({
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api();

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ? i : 0;
                        };

                        // Total over all pages
                        total = api
                            .column(11)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal = api
                            .column(11, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Update footer



                        const duration = moment.duration(total, 'seconds')
                            .format("HH:mm:ss", {
                                trim: false
                            });
                        $("#total").text(duration);
                    },

                    scrollX: true,
                    scrollY: true,
                    ajax: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'GET',
                        url: "{{ route('report.timeSheetListByUser') }}",
                        data: {
                            from: $("#from").val(),
                            to: $("#to").val(),
                            user_id: $("#user").val(),
                            department: $("#department").val(),
                            team: $("#team").val()

                        },
                    },
                    dom: 'Bfrtip',
                    pageLength: 20,
                    // stateSave: true,
                    //   processing: true,
                    // serverSide: true,
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5',

                    ],
                    columns: [

                        {
                            data: 'id',
                            title: 'ID',
                            className: "dt-right"
                        },
                        {
                            data: 'team',
                            title: 'Team',
                            className: "dt-center"
                        },
                        {
                            data: 'user_id',
                            title: 'User',
                            className: "dt-center"
                        },
                        {
                            data: 'drafting_masters_id',
                            title: 'Drafting ID',
                            className: "dt-center"
                        },
                        {
                            data: 'scheduling_masters_id',
                            title: 'Scheduling ID',
                            className: "dt-center"
                        },
                        {
                            data: 'type',
                            title: 'TYPE',
                            className: "dt-center"
                        },
                        {
                            data: 'created_at',
                            title: 'Date',
                            className: "dt-right",
                            render: function(data, type) {
                                return moment(data).format('MMM DD, YYYY');
                            }
                        },
                        {
                            data: 'morning_start',
                            title: 'Morning Start',
                            className: "dt-right"
                        },
                        {
                            data: 'morning_stop',
                            title: 'Morning Stop',
                            className: "dt-right"
                        },
                        {
                            data: 'afternoon_start',
                            title: 'Afternoon Start',
                            className: "dt-right"
                        },
                        {
                            data: 'afternoon_stop',
                            title: 'Afternoon Stop',
                            className: "dt-right"
                        },
                        {
                            data: 'hours',
                            title: 'Hours',
                            className: "dt-right",
                            render: function(data, type) {
                                const duration = moment.duration(data, 'seconds')
                                    .format("HH:mm:ss", {
                                        trim: false
                                    });
                                return duration;
                            },
                        },


                    ],
                    order: [
                        [0, 'desc']
                    ],

                });


            }
        });


        $("#clear_filter_btn").click(function() {
            $.confirm({
                icon: 'fa-solid fa-filter-circle-xmark',
                draggable: false,
                closeIcon: true,
                backgroundDismiss: true,
                type: 'orange',
                title: 'Clear filters?',
                buttons: {
                    text: 'CLEAR',
                    btnClass: 'btn-warning',
                    confirm: function() {
                        $(".filters select, .filters input").val("");
                        $("#team option , #user option").hide();
                    },
                    cancel: function() {},
                }
            });
        });

    });
</script>
