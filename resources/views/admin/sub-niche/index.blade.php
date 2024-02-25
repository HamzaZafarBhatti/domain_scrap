@extends('admin.layout.app')

@section('title', 'Niche')

@section('page_name', 'Niche')
@section('styles')
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add Niche</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('sub-niches.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Niche</label>
                                <select name="niche_id" class="form-control" id="niche">
                                    @foreach ($niche as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-primary" type="submit">Add</button>
                            <button type="button" class="btn btn-primary float-right" id="upload-button">Upload
                                File</button>
                        </div>
                    </form>
                    <form id="file-upload-form" method="POST" action="{{ route('sub-niches.import') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="niche" id="file_niche_id">
                        <input type="file" name="file" id="file-input" style="display: none;"
                            onchange="submitForm()" />

                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Niche List</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Niche</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->niche?->name }}</td>
                                    <td>{{ $item->is_active ? 'Active' : 'In active' }}</td>
                                    <td>
                                        <div class="d-flex" style="gap: 10px">
                                            <form action="{{ route('sub-niches.destroy', $item->id) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="btn btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                            <a href="{{ route('sub-niches.change_status', $item->id) }}"
                                                class="btn btn-info">
                                                @if ($item->is_active)
                                                    In active
                                                @else
                                                    Active
                                                @endif
                                            </a>
                                        </div>
                                    </td>
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
            $('#niche').select2();

        })
    </script>
    <script>
        document.getElementById('upload-button').addEventListener('click', function() {
            document.getElementById('file-input').click();
        });

        function submitForm() {
            document.getElementById('file_niche_id').value = document.getElementById('niche').value;
            document.getElementById('file-upload-form').submit();
        }
    </script>
@endsection
