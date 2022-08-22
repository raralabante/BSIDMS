@extends('layouts.app')

@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <body class="">
        <div class="container-fluid p-5">

            <ul class="nav nav-pills nav-fill" style="width: 300px">
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('my_drafts') }}">Assigned</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active realcognita " href="{{ route('my_drafts_completed') }}">Completed
                        Drafts</a>
                </li>
            </ul>
            <br>

            <table id="mydrafts_completed_tbl" class="table table-bordered row-border order-column stripe hover"
                width="100%">
            </table>

        </div>


    </body>

    <script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#draftingSubmenu .my_drafts").addClass("sidebar_active");
            $("#draftingMenu").click();

            var mydrafts_completed_tbl = $('#mydrafts_completed_tbl').DataTable({
                initComplete: function(settings, json) {
                    var scrollThead = $('#mydrafts_completed_tbl').find('thead');

                    $('tr', scrollThead).clone(false).appendTo(scrollThead);
                    console.log($(scrollThead).html());
                    $('tr:eq(1) th', scrollThead).each(function() {
                        $(this).removeClass('sorting sorting_asc sorting_desc');
                        $(this).html(
                            '<input type="text" class="form-control search_filter" placeholder="Search" />'
                        );
                    });

                    var state = mydrafts_completed_tbl.state.loaded();
                    if (state) {
                        $.each(mydrafts_completed_tbl.columns().visible(), function(colIdx, value) {
                            var colSearch = state.columns[colIdx].search;
                            if (colSearch.search) {
                                $(".search_filter:eq(" + colIdx + ")").val(colSearch.search);
                            }
                        });
                        // drafting_master_tbl.draw();
                    }

                },
                ajax: "{{ route('my_drafts_completed.list') }}",
                dom: 'Bfrtip',
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
                columns: [

                    {
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
                        title: 'Client Job Number'
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
                    // {data: 'floor_area', title: 'Floor Area', className: "dt-right"},
                    // {data: 'prospect', title: 'Prospect'},
                    // {data: 'six_stars', title: 'Six Stars'},
                    {
                        data: 'drafting_hours',
                        title: 'Drafting Hours',
                        render: function(data, type) {
                            const duration = moment.duration(data, 'seconds').format("HH:mm:ss", {
                                trim: false
                            });
                            return duration;
                        },
                    },
                    {
                        data: 'status',
                        title: 'Status',
                        className: "dt-center",
                        render: function(data, type) {

                            return getStatusColor(data);
                        },
                    },
                    {
                        data: 'created_at',
                        title: 'Created At',
                        render: function(data, type) {
                            return moment(data).format('MMM DD, YYYY');
                        },
                    },


                ],
                order: [
                    [0, 'desc']
                ],



            });

            $(mydrafts_completed_tbl.table().container()).on('keyup', 'thead input', function() {
                mydrafts_completed_tbl
                    .column($(this).parent().index() + ':visible')
                    .search(this.value)
                    .draw();
            });

            $(".clear_filters").click(function() {

                // $(".search_filter").val("");
                mydrafts_completed_tbl.state.clear();
                window.location.reload();

            });

            function getStatusColor(status) {
                color_success = ['bg-secondary', 'bg-warning text-dark', 'bg-primary', 'bg-info',
                    'bg-light text-dark', 'bg-dark', 'bg-success'
                ];
                if (status == "Unassigned") {
                    return '<span class="badge ' + color_success[0] + '">' + status + '</span>';
                } else if (status == "Assigned") {
                    return '<span class="badge ' + color_success[1] + '">' + status + '</span>';
                } else if (status == "Ready For Check") {
                    return '<span class="badge ' + color_success[2] + '">' + status + '</span>';
                } else if (status == "Ready To Submit") {
                    return '<span class="badge ' + color_success[3] + '">' + status + '</span>';
                } else if (status == "Ready For Six Stars") {
                    return '<span class="badge ' + color_success[4] + '">' + status + '</span>';
                } else if (status == "In Six Stars") {
                    return '<span class="badge ' + color_success[5] + '">' + status + '</span>';
                } else if (status == "Submitted") {
                    return '<span class="badge ' + color_success[6] + '">' + status + '</span>';
                } else {
                    return '<span class="badge ' + color_success[5] + '">' + status + '</span>';
                }
            }
        });
    </script>
@endsection
