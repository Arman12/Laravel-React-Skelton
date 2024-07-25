@extends('backend.layouts.app')

@section('content')

<div class="content-wrapper">
  <!-- <div class="page-header">
        
      </div> -->
  <div id="rowHide" class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        @if($dashboard)
        <div class="card-body">
          <h4 class="card-title justify-content-center">Update Document</h4>
          <form action="{{ route('docs.update', ['dashboard' => $dashboard]) }}" method="POST" class="form-sample">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Title</label>
                      <div class="col-sm-9">
                        <input type="text" name="title" value="{{$dashboard->title}}" class="form-control" />
                        @error('title')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">First Name</label>
                      <div class="col-sm-9">
                        <input type="text" name="firstname" value="{{$dashboard->firstname}}" class="form-control" />
                        @error('firstname')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Last Name</label>
                      <div class="col-sm-9">
                        <input type="text" name="lastname" value="{{$dashboard->lastname}}" class="form-control" />
                        @error('lastname')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Date of Birth</label>
                      <div class="col-sm-3">
                        <label>Day</label>
                        <select name="dob_day" class="form-control">
                          <option selected value="{{$dashboard->dob_day}}">{{$dashboard->dob_day}} </option>

                          @for ($x = 1; $x <= 31; $x++) <option value="{{ $x }}">
                            {{ $x }}
                            </option>
                            @endfor
                        </select>
                        @error('dob_day')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                      <div class="col-sm-3">
                        <label>Month</label>
                        <select name="dob_month" class="form-control">
                          <option selected value="{{$dashboard->dob_month}}">{{$dashboard->dob_month}} </option>
                          @for ($x = 1; $x <= 12; $x++) <option value="{{ $x }}"> {{ $x }} </option>
                            @endfor
                        </select>
                        @error('dob_month')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                      <div class="col-sm-3">
                        <label>Year</label>
                        <select name="dob_year" class="form-control">
                          <option selected value="{{$dashboard->dob_year}}"> {{$dashboard->dob_year}}</option>

                          @for ($x = 1920; $x <= 2010; $x++) <option value="{{ $x }}"> {{ $x }} </option>
                            @endfor
                        </select>
                        @error('dob_year')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <label class="col-form-label">Address *</label>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Line 1</label>
                      <div class="col-sm-9">
                        <input type="text" name="address_line1" value="{{$dashboard->address_line1}}" class="form-control" />
                        @error('address_line1')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Line 2</label>
                      <div class="col-sm-9">
                        <input type="text" name="address_line2" value="{{$dashboard->address_line2	}}" class="form-control" />
                        @error('address_line2')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Line 3</label>
                      <div class="col-sm-9">
                        <input type="text" name="address_line3" value="{{$dashboard->address_line3}}" class="form-control" />
                        @error('address_line3')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">City</label>
                      <div class="col-sm-9">
                        <input type="text" name="city" value="{{$dashboard->city}}" class="form-control" />
                        @error('city')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Country</label>
                      <div class="col-sm-9">
                        <input type="text" name="country" value="{{$dashboard->country}}" class="form-control" />
                        @error('country')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Postcode</label>
                      <div class="col-sm-9">
                        <input type="text" name="postcode" value="{{$dashboard->postcode}}" class="form-control" />
                        @error('postcode')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">

              </div>
            </div>

            <button type="submit" class="btn btn-gradient-primary mb-2">Update</button>
            <a href="{{ url('/regenerate-docs') }}" type="submit" class="btn btn-gradient-light btn-fw mb-2">Cancle</a>
          </form>
        </div>
        @endif
      </div>
    </div>
  </div>

</div>
<!-- content-wrapper ends -->
@endsection
@push('scripts')
@endpush