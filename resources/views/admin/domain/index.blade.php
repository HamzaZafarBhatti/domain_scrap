@extends('admin.layout.app')

@section('title', 'Availability of Domains')

@section('page_name', 'Availability of Domains')

@section('styles')
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        #loader {
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            /* Optional: Add a background overlay */
            background: rgba(255, 255, 255, 0.8);
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            /* Ensure it's above other elements */
        }

        .spinner {
            border: 5px solid #f3f3f3;
            /* Light grey */
            border-top: 5px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
                    <div id="loader" style="display:none;">
                        <div class="spinner"></div>
                    </div>
                    <form action="{{ route('domain.start') }}" method="post" id="domainform">
                        @csrf
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Niche</label>
                                    <select name="niche" onchange="emptyKeyword()" class="form-control" id="niche">
                                        <option value="">Select</option>
                                        @foreach ($niches as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Sub Niche</label>
                                    <select name="sub_niche" onchange="emptyKeyword()" class="form-control" id="sub_niche">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Keyword</label>
                                    <input type="text" class="form-control" name="keyword" oninput="emptyNiche()"
                                        value="{{ $keyword ?? '' }}" id="keyword">
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
            $('#domainform').submit(function() {
                // $('#loader').show(); // Show the loader
            });
            if ($('#country_multi')) {
                $('#country_multi').select2({
                    closeOnSelect: false
                });
                $('#country_multi').change(function() {
                    let value = $(this).val();
                    if (value.length < 2) {
                        $('#city_multi').val([]).change().attr('disabled', false);
                        $.ajax({
                            url: "{{ route('cities.get_by_country') }}",
                            type: 'post',
                            data: {
                                country_id: value[0],
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                console.log(data)
                                $('#city_multi').empty();
                                data.forEach(element => {
                                    $('#city_multi').append('<option value="' + element
                                        .id + '">' + element.name +
                                        '</option>');
                                });
                                $('#city_multi').select2({
                                    closeOnSelect: false
                                });
                            }
                        });
                    } else {
                        $('#city_multi').val([]).change().attr('disabled', true);
                    }
                })
            }
            if ($('#city_multi')) {
                $('#city_multi').select2({
                    closeOnSelect: false
                });
            }
            $('#niche').select2();
            $('#additional_keyword').select2();
            $('#sub_niche').select2();

        })

        function emptyNiche() {
            if ($('#keyword').val() != '') {
                $('#niche').val('').trigger('change');
            }
        }

        function emptyKeyword() {
            if ($('#niche').val() != '') {
                $('#keyword').val('');
            }
        }
        $('#niche').change(function() {
            var niche = $(this).val();
            $.ajax({
                url: "{{ route('sub-niches.index') }}",
                type: 'GET',
                data: {
                    niche: niche
                },
                success: function(data) {
                    console.log('dingo ', data)
                    $('#sub_niche').empty();
                    $('#sub_niche').append('<option value="">Select</option>');
                    data.forEach(element => {
                        $('#sub_niche').append('<option value="' + element.id + '">' + element
                            .name +
                            '</option>');
                    });
                }
            });
        });
        const keywordsInput = document.getElementById('keyword');
        const countrySelect = document.getElementById('country_multi');
        const citySelect = document.getElementById('city_multi');

        const form = document.querySelector('#domainform');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let keywords = keywordsInput.value.split(',').filter(
                Boolean); // Split by comma and remove empty strings
            const countries = Array.from(countrySelect.selectedOptions).map(option => option.value);
            const cities = Array.from(citySelect.selectedOptions).map(option => option.value);
            const niche = document.getElementById('niche').value;
            const subniche = document.getElementById('sub_niche').value;
            if (subniche) {
                keywords = 1
            } else if (niche) {
                keywords = 1
            } else {
                keywords = keywords.length
            }

            totalSelections = keywords * (countries.length + cities.length);

            if (totalSelections > 10) {
                alert('You cannot select more than 10 combined keywords, countries, and cities.');
            } else {
                $('#loader').show(); // Show the loader
                this.submit();
            }
        });
    </script>
@endsection
