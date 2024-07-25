@extends('backend.layouts.app')



@section('content')

<div class="content-wrapper">
  @if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
  @endif
  <div class="page-header">
    <h4 class="card-title justify-content-center">Campaigns</h4>
    <a href="{{ route('campaign.create') }}" type="submit" class="btn btn-primary">Add Campaign</a>
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
                <th> Name </th>
                <th> Type </th>
                <th> Email Tempalte </th>
                <th> SMS Tempalte </th>
                <th> Start Time </th>
                <th> End Time </th>
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
                <td> {{$data->name}}</td>
                <td>
                  {{$data->type}}
                </td>
                <td> {{$data->emailTemplate['title']}} </td>
                <td> {{$data->smsTemplate['title']}} </td>
                <td> {{ date('H:i', strtotime($data['start_time'])) }} </td>
                <td> {{ date('H:i', strtotime($data['end_time'])) }} </td>
                <td style="display: flex;">
                  <a class="btn btn-link btn-sm" href="{{ route('campaign.edit', ['campaign' => $data])  }}">
                    <i class="mdi mdi-tooltip-edit"></i></a>
                  <form action="{{ route('campaign.delete', ['campaign' => $data])  }}" method="POST">
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

</div>

<!-- content-wrapper ends -->
@endsection
@push('scripts')
@endpush