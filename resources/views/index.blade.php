<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/main.css')}}">


</head>
<body>




<!-- Modal -->
<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New user </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


      <div class="row">

      <form action="/add-new-user" method="post">
        @csrf
        <div class="mb-3">
          <input type="text"   class="input" name="name" required class="form-control" id="formGroupExampleInput" placeholder="Name ">
        </div>
        <div class="mb-3">
          <input type="email"  class="input" name="email" required class="form-control" id="formGroupExampleInput2" placeholder="Email ">
        </div>
        <div class="mb-3">
          <input type="password"  class="input" name="password" required class="form-control" id="formGroupExampleInput" placeholder="Password">
        </div>
        <div class="mb-3">
          <input type="password" class="input" name="confrim_password" required class="form-control" id="formGroupExampleInput2" placeholder=" Confirm Password  ">
        </div>
      </div>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
  </form>
    </div>
  </div>
</div>


<div class="container">
@if(Session::has('success'))
								<p  class="alert alert-success">{{ Session::get('success') }}</p>
							@endif

                            @if(Session::has('error'))
								<p class="alert alert-danger">{{ Session::get('error') }}</p>
							@endif
							@if($errors->any())
								@foreach ($errors->all() as $error)
									<div class="alert alert-danger"> {{$error}}</div>
								@endforeach
							@endif
</div>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1> Welcome Back 👋  </h1>
      <form action="/logout" method="post">
        @csrf 
        <button class="btn btn-primay" type="submit">
          <img src="/icon/logout.png" alt="">
          logout</button>
      </form>
    </div>
  </div>
</div>
<div id="users">
  <div class="container">
    <div class="row">
      <div class="col-md-12 ">
        <form  id="form" method="get" style="display: inline;">
        
          <input name="search" value="{{$search}}" onchange="document.getElementById('form').submit()" class="search form-control" placeholder="Search" />
        </form>

        <button class="sort btn btn-primary" data-sort="title"  data-bs-toggle="modal" data-bs-target="#addUser">
            Add New User 
        </button>
       
      </div>
    </div>
    <table class="table table-hover">
        <head>
            <tr>
                <th>NO</th>
                <th>Name</th>
                <th>Email</th>
                <th>Delete</th>
            </tr>
        </head>
        <tbody>
            @foreach($users as $key=> $value)
            <tr>
                <td>{{$key+=1}}</td>
                <td>{{$value->name}}</td>
                <td>{{$value->email}}</td>

                <td>
                  <form action="/delete-user" method="post">
                    @csrf
                    <input type="text" hidden name="user_id" value="{{$value->id}}">
                    <button class="btn btn-danger"> Delete </button>
                  </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

  </div>
</div>


<script>
  var fp = new Fingerprint({
  canvas: true,
  ie_activex: true,
  screen_resolution: true
});

var uid = fp.get();
console.log(uid);
</script>
<script src="{{asset('js/jquery.js')}}"></script>


</body>
</html>