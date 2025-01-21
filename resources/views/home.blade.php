@extends('layouts.app')

@section('content')
<div id="app">
      
        <header>

            <div class="container" style="margin-bottom: 100px;">
    
                <div class="profile">
    
                    <div class="profile-image mr-5">
    
                        <img src="{{Auth::user()->image}}" class="rounded-circle" width="100%" height="290px" alt="">
    
                    </div>
    
                    <div class="profile-user-settings">
    
                        <h1 class="profile-user-name" style="font-size:28px; font-weight:500;">{{Auth::user()->name}}</h1>
    

                        <!-- Button trigger modal -->
                        <button type="button"  class="btn profile-edit-btn" data-toggle="modal" data-target="#exampleModal">
                            Edit Profile </button>
                        
                        <!-- Modal -->
                        <div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h2 class="modal-title" id="exampleModalLabel">Edit Profile </h2>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                <form action="{{ route('profile.update', Auth::user()->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group text-center">
        <img id="output" src="{{ Auth::user()->image }}" class="rounded-circle" width="150px" height="150px">
    </div>
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}">
    </div>
    <div class="form-group">
        <label for="bio">Bio</label>
        <textarea name="bio" id="bio" cols="30" rows="5" class="form-control">{{ Auth::user()->bio }}</textarea>
    </div>
    <div class="form-group text-center">
        <input onchange="loadFile(event)" id="image" type="file" name="image" hidden accept="image/*">
        <label for="image" class="form-check-label">
            <img src="https://t4.ftcdn.net/jpg/04/81/13/43/360_F_481134373_0W4kg2yKeBRHNEklk4F9UXtGHdub3tYk.jpg" width="100" height="100" style="cursor: pointer;" alt="">
        </label>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>
                                </div>
                            </div>
                            </div>
                        </div>
                        


                 
                     
                        <a href="{{route('posts.create')}}" class="btn profile-edit-btn" aria-label="profile settings"><i class="fas fa-plus" aria-hidden="true"></i></a>
                        <button class="btn profile-settings-btn" aria-label="profile settings" data-toggle="modal" data-target="#exampleModal3"><i class="fas fa-cog" aria-hidden="true"></i></button>
                                 <!-- Modal -->
                      <div class="modal fade " id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h2 class="modal-title" id="exampleModalLabel">Change Password</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    
                                    <div class="form-group">
                                      <label for="password">Password</label>
                                      <input type="password" class="form-control" id="password" placeholder="Enter old password">
                                    </div>
                                  
                                    <div class="form-group">
                                      <label for="newpassword">New Password</label>
                                      <input type="password" class="form-control" id="newpassword" placeholder="Enter new password">
                                    </div>
                                  
                                    <div class="form-group">
                                      <label for="copassword"> Confirm Password</label>
                                      <input type="password" class="form-control" id="copassword" placeholder="Enter Confirm password">
                                    </div>
                                  
                                
                                    
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
    
    
                    </div>
    
                    <div class="profile-stats" style="margin: 30px 0px;">
    
                        <ul>
                                
                            <li><span class="profile-stat-count">{{$postcount}}</span> Posts</li>
                            
                            <li><span class="profile-stat-count">{{$followcount}}</span> Followers</li>
                            <li><span class="profile-stat-count">{{$isfollowingcount}}</span> Following</li>
                        </ul>
    
                    </div>
    
                    <div class="profile-bio" >
    
                        <p>{{Auth::user()->bio}}</p>
    
                    </div>
    
                </div>
                <!-- End of profile section -->
    
            </div>
            <!-- End of container -->
    
        </header>
    
        <main>
    
            <div class="container">
    
                <div class="gallery">
                    <div class="row ">
        
    
    
    
        
                    
    
                   @foreach ($posts as $post )
                   
                   
                <div class="gallery-item col-md-4" tabindex="0">
                     <a href="{{route('posts.show', $post->id)}}">
                        <div class="outer">
    
                            <img src="{{$post->image}}" class="gallery-image img-fluid {{$post->filter}} pt-3 pb-3" alt="" >
                            <div class="inner" style="height:275px; margin-top:12px;" >
                                <ul>
                                    <li class=""><i class="fa fa-heart" aria-hidden="true"></i> {{$post->likes->count()}}</li>
                                    <li class=""><i class="fa fa-comment" aria-hidden="true"></i> {{$post->comments->count()}}</li>
                                </ul>
        
                            </div>
                        </div>
    
                    </a>
    
    
                </div>

                @endforeach
               
    
    
     
     
    
                </div>
             </div>
    
    
            </div>
    
        </main>
    </div>
@endsection
