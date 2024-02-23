@extends('admin.layout.app')

@section('title', 'Cities')

@section('page_name', 'Cities')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add City</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('cities.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-primary" type="submit">Add</button>
                            <button type="button" class="btn btn-primary float-right" id="upload-button">Upload
                                File</button>
                        </div>
                    </form>
                    <form id="file-upload-form" method="POST" action="{{ route('cities.import') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" id="file-input" style="display: none;" onchange="submitForm()" />

                </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Cities</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cities as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->is_active ? 'Active' : 'In active' }}</td>
                                    <td>
                                        <div class="d-flex" style="gap: 10px">
                                            <form action="{{ route('cities.destroy', $item->id) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="btn btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                            <a href="{{ route('cities.change_status', $item->id) }}" class="btn btn-info">
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
                                <tr>
                                    <td colspan="4">No Records!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('upload-button').addEventListener('click', function() {
                document.getElementById('file-input').click();
            });

            function submitForm() {
                console.log('submitting', document.getElementById('file-upload-form'));
                document.getElementById('file-upload-form').submit();
            }
    </script>
@endsection
