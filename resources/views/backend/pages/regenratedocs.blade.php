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
              <label for="date">Search:</label>
              <input type="text" class="form-control" id="search" name="search" placeholder="Search" autocomplete="off">
            </div>
            <button type="submit" style="margin-top: 23px !important;" class="btn btn-primary">Apply Filters</button>
            <button id="clearFilter" style="margin-top: 23px !important;" class="btn btn-danger">clear Filters</button>
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


          <!-- <p class="card-description"> Total Leads <code>.table-bordered</code>
                    </p> -->
          <table class="table table-bordered">
            <thead>
              <tr>
                <th> # </th>
                <th> Full name </th>
                <th> Email </th>
                <th> Phone </th>
                <!-- <th> Opt In </th> -->
                <th> Link </th>
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
                <td> {{$data->title . ' ' . $data->firstname . ' ' . $data->lastname}}</td>
                <td>
                  {{$data->email}}
                </td>
                <td> {{$data->phone}} </td>
                <!-- <td> {{$data->createdAt}} </td> -->
                <td> {{$data->PostURL}} </td>
                <td> <a tabindex="0" onclick="copyText('{{$data->aml_pdf_url}}',event)" class="btn btn-link btn-sm" data-toggle="popover" data-trigger="focus" data-content="Copy to Clipboard" data-placement="top" href="{{$data->aml_pdf_url}}">
                    <i class="mdi mdi-content-copy"></i></a>
                    <a class="btn btn-link btn-sm" href="{{ route('docs.edit', ['dashboard' => $data])  }}">
                    <i class="mdi mdi-tooltip-edit"></i></a>
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

  $('#search-form').on('submit', function(event) {
    event.preventDefault();
    var search = $('#search').val();

    $('#new_search').val(search);
    $.ajax({
      type: 'POST',
      url: '/regenerate-docs',
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

  $('#clearFilter').click(function(){
        $('#search').val('');
        window.location.href = '/regenerate-docs';
    });
</script>
@endpush