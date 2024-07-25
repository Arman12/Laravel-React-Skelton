@extends('backend.layouts.app')


@section('content')

<div class="content-wrapper">
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
      <div class="page-header">
        <h4 class="card-title justify-content-center">Email Templates</h4>
        <a href="{{ route('email.create') }}" type="submit" class="btn btn-primary">Add Email Template</a>
      </div>
  <div id="rowHide" class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        @if(isset($result))
        <div class="card-body">
          <h4 class="card-title justify-content-center">Showing Leads {{ $result->count() }} of {{ $result->total() }}</h4>


          <!-- <p class="card-description"> Total Leads <code>.table-bordered</code>
                    </p> -->
          <table class="table table-bordered">
            <thead>
              <tr>
                <th> # </th>
                <th> Title </th>
                <th> Subject </th>
                <th> Description </th>
                <th> Status </th>
                <th> Action </th>
              </tr>
            </thead>
            @php
            $continuousCount = ($result->currentPage() - 1) * $result->perPage() + 1;
            @endphp
            <tbody>
              @foreach($result as $data)
              <tr>
                <td> {{ $continuousCount }} </td>
                <td> {{$data->title}}</td>
                <td>
                  {{$data->subject}}
                </td>
                <td> {{ \Illuminate\Support\Str::limit(strip_tags($data->description), 70, '...') }} </td>
                <td> {{$data->status}} </td>
                <td style="display: flex;"> 
                  <a class="btn btn-link btn-sm" href="{{ route('email.edit', ['emailTemplate' => $data])  }}">
                  <i class="mdi mdi-tooltip-edit"></i></a>
                  <form action="{{ route('email.delete', ['emailTemplate' => $data])  }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-link btn-sm ">
                    <i class="mdi mdi-delete"></i></button>
                  </form>
                </td>
              </tr>
              @php
              $continuousCount++;
              @endphp
              @endforeach

            </tbody>
            {{ $result->links('pagination::bootstrap-4', ['class' => 'custom-pagination']) }}

          </table>
        </div>
        @endif
      </div>
    </div>
  </div>
  <div id="search-results">
    <!-- AJAX response will be displayed here -->
  </div>

</div>

<!-- content-wrapper ends -->
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('.alert').hide();
        }, 3000); // 3000 milliseconds = 3 seconds
    });
</script>
@endpush