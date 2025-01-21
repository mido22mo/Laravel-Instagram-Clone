@extends('layouts.app')

@section('content')
<div id="app">
       


        <div class="container">
            <h1 class="text-center mt-5 mb-5">Add Post</h1>


            <div class="row">
                <div class="col-md-6">
                    
                    <form action="{{route('posts.store')}}" method="post" enctype="multipart/form-data" class="createform p-5 w-75 mx-auto">
                        @csrf
                        <div class="form-group">
                            <label>Upload Image</label>
                            <input value="{{old('image')}}" onchange="loadFile(event)" id="image" type="file" name="image"  class="form-control">
                          </div>

                          <p class="text-danger">@error('image') {{$message}} @enderror </p>
        
                        <div class="form-group">
                          <label>Title</label>
                          <input value="{{old('caption')}}" type="text" name="caption" class="form-control">
                        </div>

                        <p class="text-danger">@error('caption') {{$message}} @enderror </p>

                        <input value="original"  type="text" name="filter" id="filterresult" hidden>
                        
                        <button type="submit" class="btn btn-dark p-2 w-100" style="background-color: black; color: #fff;">Submit</button>
                      </form>
                </div>
                 

                <div class="col-md-6">
                    <div class="img-container text-center">
                        <img id="output" src="{{Auth::user()->image}}" width="100%" alt="" height="450px">
                        <div class="botns my-5">
                            <button onclick="ChangeFilter('1')" class="btn btn-dark">Black & White</button>
                            <button onclick="ChangeFilter('2')" class="btn btn-dark">Saturate</button>
                            <button onclick="ChangeFilter('3')" class="btn btn-dark">Brightness</button>
                            <button onclick="ChangeFilter('4')" class="btn btn-dark">Invert</button>
                            <button onclick="ChangeFilter('5')" class="btn btn-dark">Hue Rotate</button>
                            <button onclick="ChangeFilter('0')" class="btn btn-dark">Orginal</button>
                        </div>
                    </div>
                </div>
    
            </div>
            
                
        </div>
</div>
@endsection