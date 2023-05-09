@extends('layouts.app-admin')
@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col-sm-12 col-md-8 col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-center">Ganti Password</h5>
            <form action="{{url('/postChangePass')}}" method="POST">
              @csrf
              <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input type="email" class="form-control" id="emailDisplay" placeholder="Cari Email" required>
                <input type="hidden" class="form-control" id="email" name="email" required>
              </div>
              <div id="div-password"></div>
              <div class="text-center">
                <div class="row mb-3">
                    <a id="cek" class="btn btn-success">Cek</a>
                </div>
                <div class="row">
                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/change_password.js')}}"></script>
@endsection
