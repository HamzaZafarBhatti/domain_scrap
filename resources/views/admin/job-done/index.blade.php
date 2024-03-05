@extends('admin.layout.app')

@section('title', 'Processed Jobs')

@section('page_name', 'Processed Jobs')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Processed Jobs List</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Country</th>
                                <th>City</th>
                                <th>Niche</th>
                                <th>Sub Niche</th>
                                <th>Status</th>
                                <th>Domain</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jobs as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->is_country ? 'Yes' : 'No' }}</td>
                                    <td>{{ $item->is_city ? 'Yes' : 'No' }}</td>
                                    <td>{{ $item->is_niche ? 'Yes' : 'No' }}</td>
                                    <td>{{ $item->is_sub_niche ? 'Yes' : 'No' }}</td>
                                    <td>{{ $item->status}}</td>
                                    <td>{{ $item->domain}}</td>
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
