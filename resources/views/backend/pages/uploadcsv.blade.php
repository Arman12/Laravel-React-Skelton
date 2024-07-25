@extends('backend.layouts.app')

@section('content')
<style>
  .filter-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: flex-start;
  }

  select.form-control {
    padding: 1.01rem 0.75rem !important;
  }
</style>
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <form action="{{route('upload.csv')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group col-lg-3">
              <label for="date">CSV File:</label>
              <input type="file" class="form-control" name="csv_file" placeholder="Uload csv file">
            </div>
            <button type="submit" class="btn btn-primary">Upload CSV</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
<!-- content-wrapper ends -->
@endsection