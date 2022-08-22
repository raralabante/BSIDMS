@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .ui-widget,
        #drafters {
            z-index: 10000;
        }
    </style>

    <body>
        <div class="content">
            <div class="container-fluid p-5 "style="overflow: scroll">
                <div class="row">
                    <div class="">
                        @if (session()->has('success'))
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-check"></i>&nbsp;
                                <div>
                                    {{ session()->get('success') }}
                                </div>
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-triangle"></i>&nbsp;
                                <div>
                                    {{ session()->get('error') }}
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <br>
                        <table id="drafting_master_cancelled_tbl"
                            class="table table-bordered row-border order-column stripe hover" width="100%">
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </body>



    <script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#draftingSubmenu .cancelled").addClass("sidebar_active");
            $("#draftingMenu").click();
            const toastLiveExample = document.getElementById('liveToast')
            const toast = new bootstrap.Toast(toastLiveExample);
            const warningToast = document.getElementById('warningToast')
            const toastWarning = new bootstrap.Toast(warningToast);

            var drafting_master_cancelled_tbl = $('#drafting_master_cancelled_tbl').DataTable({
                initComplete: function(settings, json) {
                    var scrollThead = $('#drafting_master_cancelled_tbl').find('thead');

                    $('tr', scrollThead).clone(false).appendTo(scrollThead);
                    console.log($(scrollThead).html());
                    $('tr:eq(1) th', scrollThead).each(function() {
                        $(this).removeClass('sorting sorting_asc sorting_desc');
                        $(this).html(
                            '<input type="text" class="form-control search_filter" placeholder="Search" />'
                        );
                    });

                    var state = drafting_master_cancelled_tbl.state.loaded();
                    if (state) {
                        $.each(drafting_master_cancelled_tbl.columns().visible(), function(colIdx,
                            value) {
                            var colSearch = state.columns[colIdx].search;
                            if (colSearch.search) {
                                $(".search_filter:eq(" + colIdx + ")").val(colSearch.search);
                            }
                        });
                        // drafting_master_tbl.draw();
                    }

                },
                ajax: "{{ route('drafting_master_fetch_by_status_list.list', '') }}" + "/Cancelled",
                dom: 'Bfrtip',
                colReorder: true,
                stateSave: true,
                //   processing: true,
                // serverSide: true,
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5',

                    {
                        className: 'btn-dark-green clear_filters border-0',
                        text: 'Clear Filters',
                    },

                ],
                columns: [{
                        data: 'id',
                        title: 'ID',
                        className: "dt-right"
                    },
                    {
                        data: 'customer_name',
                        title: 'Customer'
                    },
                    {
                        data: 'job_number',
                        title: 'Client Job Number',
                        className: "dt-center"
                    },
                    {
                        data: 'client_name',
                        title: 'Client Name'
                    },
                    {
                        data: 'address',
                        title: 'Address'
                    },
                    {
                        data: 'type',
                        title: 'Type'
                    },
                    {
                        data: 'ETA',
                        title: 'ETA',
                        className: "dt-right",
                        render: function(data, type) {
                            return moment(data).format('MMM DD, YYYY');
                        }
                    },
                    {
                        data: 'brand',
                        title: 'Brand'
                    },
                    {
                        data: 'job_type',
                        title: 'Job Type'
                    },
                    {
                        data: 'category',
                        title: 'Category'
                    },
                    {
                        data: 'floor_area',
                        title: 'Floor Area',
                        className: "dt-right"
                    },
                    {
                        data: 'prospect',
                        title: 'Prospect'
                    },
                    {
                        data: 'six_stars',
                        title: 'Six Stars'
                    },
                    {
                        data: 'drafters',
                        title: 'Drafters',
                        className: "dt-center"
                    },
                    {
                        data: 'drafting_hours',
                        title: 'Drafting Hours',
                        className: "dt-center",
                        render: function(data, type) {
                            const duration = moment.duration(data, 'seconds').format("HH:mm:ss", {
                                trim: false
                            });
                            return duration;
                        },
                    },
                    {
                        data: 'checker',
                        title: 'Checker',
                        className: "dt-center"
                    },
                    {
                        data: 'checking_hours',
                        title: 'Checking Hours',
                        className: "dt-center",
                        render: function(data, type) {
                            const duration = moment.duration(data, 'seconds').format("HH:mm:ss", {
                                trim: false
                            });
                            return duration;
                        },
                    },
                    {
                        data: 'total_hours',
                        title: 'Total Hours',
                        className: "dt-center",
                        render: function(data, type) {
                            const duration = moment.duration(data, 'seconds').format("HH:mm:ss", {
                                trim: false
                            });
                            return duration;
                        },
                    },
                    {
                        data: 'created_at',
                        title: 'Created At',
                        className: "dt-right",
                        render: function(data, type) {
                            return moment(data).format('MMM DD, YYYY');
                        },
                    },

                ],
                order: [
                    [0, 'desc']
                ],

            });

            $(drafting_master_cancelled_tbl.table().container()).on('keyup', 'thead input', function() {
                drafting_master_cancelled_tbl
                    .column($(this).parent().index() + ':visible')
                    .search(this.value)
                    .draw();
            });

            $(".clear_filters").click(function() {

                // $(".search_filter").val("");
                drafting_master_cancelled_tbl.state.clear();
                window.location.reload();

            });

            Pusher.logToConsole = true;

            var pusher = new Pusher('89eec464cd4d14a2238d', {
                cluster: 'ap1'
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(data) {
                drafting_master_cancelled_tbl.ajax.reload();

            });

        });
    </script>
@endsection
