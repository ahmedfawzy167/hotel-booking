@extends('layouts.master')

@section('page-title')
    {{ __('admin.All Hotels') }}
@endsection

@section('page-content')
    <div class="row">
        <div class="card-body">
            <div class="table-responsive">
                <h1 class="text-center bg-primary text-light"><i class="fa-solid fa-list"></i> {{ __('admin.All Hotels') }}
                </h1>
                <table class="table table-hover table-bordered" id="data-table">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('admin.ID') }}</th>
                            <th>{{ __('admin.Name') }}</th>
                            <th>{{ __('admin.Address') }}</th>
                            <th>{{ __('admin.Image') }}</th>
                            <th>{{ __('admin.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hotels as $hotel)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $hotel->name }}</td>
                                <td>{{ $hotel->address }}</td>
                                <td>
                                    @foreach ($hotel->images as $image)
                                        <img src="{{ asset('storage/' . $image->path) }}" width="70px" class="mr-2">
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ route('hotels.show', $hotel->id) }}"
                                        class="btn btn-info">{{ __('admin.Show') }}</a>
                                    <a href="{{ route('hotels.edit', $hotel->id) }}"
                                        class="btn btn-success">{{ __('admin.Edit') }}</a>
                                    <form action="{{ route('hotels.destroy', $hotel->id) }}" method="post"
                                        style="display: inline-block">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger"
                                            style="display: inline-block">{{ __('admin.Trash') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <h1 class="text-center">No Hotels Found!</h1>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

        @include('layouts.messages')


    @endsection
