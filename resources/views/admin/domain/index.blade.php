@extends('admin.layout.app')

@section('title', 'Availability of Domains')

@section('page_name', 'Availability of Domains')

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
                    <h4>Availability</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('domain.start') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Niche</label>
                                    <select name="niche" class="form-control" id="niche">
                                        <option value="">Select</option>
                                        @foreach ($niches as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Keyword</label>
                                    <input type="text" class="form-control" name="keyword" value="{{ $keyword ?? '' }}"
                                        id="keyword" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Additional Keyword</label>
                                    <select name="additional_keyword" class="form-control" id="additional_keyword">
                                        <option value="">Select</option>
                                        @foreach ($keywords as $item)
                                            <option value="{{ $item->name }}">{{ ucfirst($item->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Year</label>
                                    <select name="year" class="form-control">
                                        @for ($i = 2000; $i <= 2024; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Country</label>
                                    <select name="country_id[]" class="form-control" id="country_multi" multiple>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>City</label>
                                    <select name="city_id[]" class="form-control" id="city_multi" multiple>
                                        @foreach ($cities as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-primary" type="submit">Start</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if (session()->has('domains'))
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Available Domains</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (session('domains') as $item)
                                    <tr class="bg-success text-white">
                                        <td>{{ $item }}</td>
                                    </tr>
                                @empty
                                    <tr class="bg-danger text-white">
                                        <td>No Available Domains!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            if ($('#country_multi')) {
                $('#country_multi').select2();
            }
            if ($('#city_multi')) {
                $('#city_multi').select2();
            }
            $('#niche').select2();
            $('#additional_keyword').select2();
        })
    </script>
@endsection
