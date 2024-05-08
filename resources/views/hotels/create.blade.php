@extends('layouts.master')

@section('page-title')
  {{__('admin.New Hotel')}}
@endsection

@section('page-content')
<div class="card">
 <div class="card-body container">
   <h1 class="text-center bg-primary text-white"><i class="ion-plus-circled"></i> {{__('admin.Add New Hotel')}}</h1>
   <form action="{{ route('hotels.store') }}" method="POST" enctype="multipart/form-data" class="row">
    @csrf
    <div class="form-group col-md-12">
      <label for="name"><i class="fa-solid fa-file-signature"></i> {{__('admin.Hotel Name')}}</label>
      <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
      @error('name')
        <strong class="invalid-feedback" role="alert">{{ $message }}</strong>
      @enderror
    </div>

    <div class="form-group col-md-12">
        <label for="description"><i class="ion-ios-albums"></i> {{__('admin.Description')}}</label>
        <textarea type="text" name="description" id="summernote" class="form-control @error('description') is-invalid @enderror"></textarea>
        @error('description')
         <strong class="invalid-feedback" role="alert">{{ $message }}</strong>
        @enderror
      </div>

      <div class="form-group col-md-12">
        <label for="address"><i class="ion-ios-bookmarks"></i> {{__('admin.Address')}}</label>
        <input type="text" name="address" id="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror">
        @error('address')
          <strong class="invalid-feedback" role="alert">{{ $message }}</strong>
        @enderror
      </div>

      <div class="form-group col-md-12">
        <label for="images"><i class="ion-images"></i> {{__('admin.Image')}}</label>
        <input type="file" name="images[]" multiple value="{{ old('images') }}"  id="images" class="form-control @error('images') is-invalid @enderror">
        @error('images')
          <strong class="invalid-feedback" role="alert">{{ $message }}</strong>
        @enderror
      </div>

     <div class="text-center">
       <button type="submit" class="btn btn-primary btn-lg">{{__('admin.Add')}}</button>
       <button type="reset" class="btn btn-secondary btn-lg">{{__('admin.Reset')}}</button>
     </div>

</form>
</div>
</div>
@endsection
