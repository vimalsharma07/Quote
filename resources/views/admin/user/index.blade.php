@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __("CUSTOMER") }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("Customers") }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-user-index') }}">{{ __("Customers") }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('includes.admin.form-success')
                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __("Name") }}</th>
                                        <th>{{ __("Email") }}</th>
                                        <th>Phone</th>
                                        <th>Created At</th>
                                        <th>{{ __("Options") }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ADD / EDIT MODAL --}}
    <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- ADD / EDIT MODAL ENDS --}}
    {{-- DELETE MODAL --}}
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __("Confirm Delete") }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">{{ __("You are about to delete this Customer.") }}</p>
                    <p class="text-center">{{ __("Do you want to proceed?") }}</p>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __("Cancel") }}</button>
                    <a class="btn btn-danger btn-ok">{{ __("Delete") }}</a>
                </div>
            </div>
        </div>
    </div>
    {{-- DELETE MODAL ENDS --}}
@endsection

@section('scripts')
    {{-- DATA TABLE --}}
    <script type="text/javascript">
        $(document).ready(function() {

            function fetchDataTable() {
                console.log('Fetching data table');

                var table = $('#geniustable').DataTable({
                    destroy: true,
                    ordering: true,
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    ajax: {
                        url: '{{ route('admin-user-datatables') }}',
                        type: 'GET',
                        dataSrc: function(json) {
                            console.log('Received data:', json);
                            return json.data;
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            console.log('Response Text:', xhr.responseText);
                        }
                    },
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'phone', name: 'phone' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action', searchable: false, orderable: false }
                    ],
                    language: {
                        processing: "Processing...",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Previous"
                        }
                    },
                    order: [[3, 'desc']]
                });

                table.on('xhr.dt', function(e, settings, json, xhr) {
                    console.log('DataTable XHR event triggered');
                    console.log('JSON data:', json);
                });

                console.log('DataTable initialized:', table);
            }

            fetchDataTable();

           

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                fetchDataTable();
            });

            $('#refresh-table').on('click', function() {
                fetchDataTable();
            });

            fetchDataTable();
        });
    </script>
    {{-- DATA TABLE --}}
@endsection
