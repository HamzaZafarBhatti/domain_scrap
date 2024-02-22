@extends('admin.layout.app')

@section('title', 'Users')

@section('page_name', 'Users')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="w-100">
                        <h4>Users</h4>
                    </div>
                    <div class="text-right w-100">
                        <a type="button" class="btn btn-primary btn-sm" href="{{ route('users.create') }}">Add User</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->role->label() }}</td>
                                    <td>
                                        <div class="d-flex" style="gap: 10px">
                                            <a href="{{ route('users.edit', $item->id) }}" class="btn btn-info"
                                                type="button">Edit</a>
                                            <form action="{{ route('users.destroy', $item->id) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="btn btn-danger">
                                                    Delete
                                                </button>
                                            </form>
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
        })
    </script>
@endsection