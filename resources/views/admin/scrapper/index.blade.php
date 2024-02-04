@extends('admin.layout.app')

@section('title', 'Scrap Domains')

@section('page_name', 'Scrap Domains')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Scrap</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('scrapper.start') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Keyword</label>
                                    <input type="text" class="form-control" name="keyword" id="keyword" required>
                                </div>
                            </div>
                            {{-- <div class="col-6">
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control" name="keyword" id="keyword" required>
                                </div>
                            </div> --}}
                        </div>
                        <div>
                            <button class="btn btn-primary" type="submit">Start</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
