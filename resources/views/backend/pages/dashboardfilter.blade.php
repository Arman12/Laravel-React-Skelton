
  <div class="row">
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
        @else
        <div class="card-body">
          <h4 class="card-title justify-content-center">No result found</h4>
        </div>
        @endif
      </div>
    </div>
  </div>
