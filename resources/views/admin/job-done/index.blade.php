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
                                <th>Date</th>
                                <th>Country</th>
                                <th>City</th>
                                <th>Niche</th>
                                <th>Sub Niche</th>
                                <th>Domain Tlds</th>
                                <th>Status</th>
                                <th>progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jobs as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{\Carbon\Carbon::parse($item->created_at)->format('Y-m-d')}}</td>
                                    <td>{{ $item->country_name }}</td>
                                    <td>{{ $item->city_name }}</td>
                                    <td>{{ $item->niche_name }}</td>
                                    <td>{{ $item->sub_niche_name }}</td>
                                    <td>{{$item->domain_tlds}}</td>
                                    <td>{{ $item->status}}</td>
                                    <td>{{ $item->progress}}</td>
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
