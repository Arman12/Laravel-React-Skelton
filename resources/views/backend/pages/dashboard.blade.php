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
          <form id="search-form" class="filter-form">
            @csrf
            <div class="form-group col-lg-3">
              <label for="date">Start Date:</label>
              <input type="text" class="form-control" name="start_date" id="date" placeholder="Select a Date" autocomplete="off">
            </div>
            <div class="form-group col-lg-3">
              <label for="endDate">End Date:</label>
              <input type="text" class="form-control" name="end_date" id="endDate" placeholder="Select a Date" autocomplete="off">
            </div>
            <div class="form-group col-lg-3">
              <label for="category">PostUrl:</label>
              <select class="form-control" name="PostURL" id="PostURL">
                <option value="">Select Category</option>
                <option value="https://dieselengine.claims/V6MULTI/">https://dieselengine.claims/V6MULTI/</option>
                <option value="https://dieselengine.claims/V6seopa/">https://dieselengine.claims/V6seopa/</option>
                <option value="https://dieselengine.claims/V6BMW/">https://dieselengine.claims/V6BMW/</option>
              </select>
            </div>
            <div class="form-group col-lg-3">
              <label for="price">Classification:</label>
              <select class="form-control" name="is_completed" id="is_completed">
                <option value="">Select Classification</option>
                <option value="0">Partial Leads</option>
                <option value="1">Completed Leads</option>
              </select>
            </div>
            <div class="form-group col-lg-3">
              <label for="search">Search:</label>
              <input type="text" class="form-control" id="search" name="search" placeholder="Search" autocomplete="off">
            </div>
            <button type="submit" style="margin-top: 23px !important;" class="btn btn-primary">Apply Filters</button>
            <button id="clearFilter" style="margin-top: 23px !important;" class="btn btn-danger">clear Filters</button>
          </form>
          <form action="{{url('/download-csv')}}" method="get">
            @csrf
            <input type="hidden" id="new_start_date" name="start_date" placeholder="Start Date">
            <input type="hidden" id="new_end_date" name="end_date" placeholder="End Date">
            <input type="hidden" id="new_PostURL" name="PostURL" placeholder="Post URL">
            <input type="hidden" id="new_is_completed" name="is_completed" placeholder="Post URL">
            <input type="hidden" id="new_search" name="search" autocomplete="off">
            <button type="submit" class="btn btn-primary">Download CSV</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="page-header">
        
      </div> -->
  <div id="rowHide" class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        @if($result)
        <div class="card-body">
          <h4 class="card-title justify-content-center">Showing Leads {{ $result->count() }} of {{ $result->total() }}</h4>

          <table class="table table-bordered">
            <thead>
              <tr>
                <th> # </th>
                <th> Full name </th>
                <th> Email </th>
                <th> Date/Time </th>
                <th> Phone </th>
                <th> Reference </th>
                <th> Link </th>
              </tr>
            </thead>
            @php
            $continuousCount = ($result->currentPage() - 1) * $result->perPage() + 1;
            @endphp
            <tbody>
              @foreach($result as $data)
              <tr>
                <td> {{ $continuousCount }} </td>
                <td> {{$data->title . ' ' . $data->firstname . ' ' . $data->lastname}}</td>
                <td>
                  {{$data->email}}
                </td>
                <td> {{$data->createdAt}} </td>
                <td> {{$data->phone}} </td>
                <td> {{$data->PostURL}} </td>
                <td> <a tabindex="0" onclick="copyText('{{$data->PostURL}}',event)" class="copy_text" data-toggle="popover" data-trigger="focus" data-content="Copy to Clipboard" data-placement="top" href="{{$data->PostURL}}">
                    <i class="mdi mdi-content-copy"></i></a> </td>
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
  function copyText(url, e) {
    e.preventDefault();
    var result = copyToClipboard(url);
    console.log("copied?", result);
  }

  function copyToClipboard(text) {
    if (window.clipboardData && window.clipboardData.setData) {
      // IE specific code path to prevent textarea being shown while dialog is visible.
      return clipboardData.setData("Text", text);

    } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
      var textarea = document.createElement("textarea");
      textarea.textContent = text;
      textarea.style.position = "fixed"; // Prevent scrolling to bottom of page in MS Edge.
      document.body.appendChild(textarea);
      textarea.select();
      try {
        return document.execCommand("copy"); // Security exception may be thrown by some browsers.
      } catch (ex) {
        console.warn("Copy to clipboard failed.", ex);
        return false;
      } finally {
        document.body.removeChild(textarea);
      }
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    var today = new Date();

    var picker = new Pikaday({
      field: document.getElementById('date'),
      format: 'YYYY-MM-DD', // Choose your desired date format
      toString(date, format) {
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();
        return `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
      },
      maxDate: today, // Set maximum date as today
      defaultDate: today // Preselect today's date
    });
  });
  document.addEventListener('DOMContentLoaded', function() {
    var today = new Date();

    var picker = new Pikaday({
      field: document.getElementById('endDate'),
      format: 'YYYY-MM-DD', // Choose your desired date format
      toString(date, format) {
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();
        return `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
      },
      maxDate: today,
      defaultDate: today // Preselect today's date
    });
  });

  $('#search-form').on('submit', function(event) {
    event.preventDefault();
    var startDate = $('#date').val();
    var endDate = $('#endDate').val();
    var postURL = $('#PostURL').val();
    var is_completed = $('#is_completed').val();
    var search = $('#search').val();

    $('#new_start_date').val(startDate);
    $('#new_end_date').val(endDate);
    $('#new_PostURL').val(postURL);
    $('#new_is_completed').val(is_completed);
    $('#new_search').val(search);
    $.ajax({
      type: 'POST',
      url: '/dashboard',
      data: $(this).serialize(),
      success: function(response) {
        if (response.trim() !== '') {
          $('#rowHide').hide();
          $('#search-results').html(response);
        }
      },
      error: function(error) {
        console.log(error);
      }
    });
  });

  $('#clearFilter').click(function() {
    $('#start_date').val('');
    $('#end_date').val('');
    $('#search').val('');
    $('#partial').val('0');
    window.location.href = '/dashboard';
  });
</script>
@endpush