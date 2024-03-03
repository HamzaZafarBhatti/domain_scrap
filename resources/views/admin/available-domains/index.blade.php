@extends('admin.layout.app')

@section('title', 'Available Domains')

@section('page_name', 'Available Domains')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Available Domains List</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                {{-- <th>#</th> --}}
                                <th>Name</th>
                                <th>Date</th>
                                <th>Niche</th>
                                <th>Sub Niche</th>
                                <th>Job</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($domains as $item)
                                <tr>
                                    {{-- <td>{{ $loop->iteration }}</td> --}}
                                    <td>{{ $item->domain_name }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ \ucfirst($item->niche ? $item->niche->name : $item->subNiche?->niche?->name) }}</td>
                                    <td>{{ \ucfirst($item->subNiche?->name) }}</td>
                                    <td>{{ $item->is_job ? 'Yes' : 'No' }}</td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        })
    </script>
@endsection
