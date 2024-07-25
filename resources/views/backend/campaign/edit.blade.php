@extends('backend.layouts.app')


@section('content')

<div class="content-wrapper">
  <style>
    .tag-wrap span.tag-label {
      background: rgba(135, 129, 189, 0.3);
      border-radius: 3px;
      padding: 4px 11px;
      margin: 2px 2px;
      font-size: 14px;
      color: #000;
      text-align: center;
      display: inline-block;
      cursor: pointer;
    }
  </style>
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">

      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Edit Campaign</h4>
          @if(isset($campaign))
          <form action="{{ route('campaign.update', ['campaign' => $campaign]) }}" method="POST" class="form-sample">
            @csrf
            <div class="row">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Name</label>
                  <div class="col-sm-9">
                    <input type="text" name="name" value="{{$campaign->name}}" class="form-control" />
                    @error('name')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Description</label>
                  <div class="col-sm-9">
                    <textarea name="description" class="form-control" rows="10">{{$campaign->description}}</textarea>
                    @error('description')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Campaign Type</label>
                  <div class="col-sm-9">
                    <select name="type" class="form-control">
                      <option selected disabled>Select </option>
                      <option value="email" {{ $campaign->type === 'email' ? 'selected' : '' }}>Email </option>
                      <option value="sms" {{ $campaign->type == 'sms' ? 'selected' : '' }}>SMS</option>
                      <option value="email/sms" {{ $campaign->type === 'email/sms' ? 'selected' : '' }}>Email/SMS</option>
                    </select>
                    @error('type')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Select Email Template</label>
                  <div class="col-sm-9">
                    <select name="email_template_id" class="form-control">
                      <option selected disabled>Select </option>
                      @if(isset($emailTemplate))
                      @foreach($emailTemplate as $email)
                      <option value="{{$email->id}}" {{ $campaign->email_template_id === $email->id ? 'selected' : '' }}>{{$email->title}} </option>
                      @endforeach
                      @endif
                    </select>
                    @error('email_template_id')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Select SMS Template</label>
                  <div class="col-sm-9">
                    <select name="sms_template_id" class="form-control">
                      <option selected disabled>Select </option>
                      @if(isset($smsTemplate))
                      @foreach($smsTemplate as $sms)
                      <option value="{{$sms->id}}" {{ $campaign->sms_template_id === $sms->id ? 'selected' : '' }}>{{$sms->title}} </option>
                      @endforeach
                      @endif
                    </select>
                    @error('sms_template_id')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Select Lead From (elapsed time)</label>
                  <div class="col-sm-9">
                    <select name="datafrom" class="form-control">
                      <option selected disabled>Select </option>
                      @for ($x = 0; $x <= 100; $x++) <option value="{{ $x }}" {{ $campaign->datafrom === $x ? 'selected' : '' }}>{{ $x.' days' }}</option>
                        @endfor
                    </select>
                    @error('datafrom')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Select Recursion</label>
                  <div class="col-sm-9">
                    <select name="recursion" class="form-control">
                      <option selected disabled>Select </option>
                      <option value="once" {{ $campaign->recursion === 'once' ? 'selected' : '' }}>Once</option>
                      <option value="hourly" {{ $campaign->recursion === 'hourly' ? 'selected' : '' }}>Hourly</option>
                      <option value="daily" {{ $campaign->recursion === 'daily' ? 'selected' : '' }}>Daily</option>
                      <option value="weekly" {{ $campaign->recursion === 'weekly' ? 'selected' : '' }}>Weekly</option>
                      <option value="monthly" {{ $campaign->recursion === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                    @error('recursion')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Select Start Time</label>
                  <div class="col-sm-9" style="display: flex;">
                    <select id="hourSelect" class="form-control">
                      @php
                      $startTime = $campaign->start_time;
                      list($hours, $minutes) = explode(':', $startTime);
                      @endphp
                      <option value="{{$hours}}" selected>{{$hours.' hour'}}</option>
                      @for ($x = 0; $x <= 23; $x++) <option value="{{ $x }}">
                      {{ $x.' hour' }}
                      </option>
                      @endfor
                    </select>

                    <select id="minuteSelect" class="form-control">
                      <option selected value="{{ $minutes }}">{{ $minutes.' minute' }} </option>
                      
                      @for ($x = 0; $x <= 23; $x++) <option value="{{ $x }}">
                      {{ $x.' minute' }}
                      </option>
                      @endfor
                    </select>
                    @error('start_time')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Select End Time</label>
                  <div class="col-sm-9" style="display: flex;">
                    <select id="endHourSelect" class="form-control">
                      @php
                      $endTime = $campaign->end_time;
                      list($hour, $minute) = explode(':', $endTime);
                      @endphp
                      <option value="{{$hour}}" selected>{{$hour.' hour'}}</option>
                      @for ($x = 0; $x <= 23; $x++) <option value="{{ $x }}">
                      {{ $x.' hour' }}
                      </option>
                      @endfor
                    </select>

                    <select id="endMinuteSelect" class="form-control">
                      <option selected value="{{ $minute }}">{{ $minute.' minute' }} </option>
                      
                      @for ($x = 0; $x <= 23; $x++) <option value="{{ $x }}">
                      {{ $x.' minute' }}
                      </option>
                      @endfor
                    </select>
                    @error('end_time')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="start_time" id="combinedTime" value="{{$campaign->start_time}}">
            <input type="hidden" name="end_time" id="endCombinedTime" value="{{$campaign->end_time}}">
            <button type="submit" class="btn btn-gradient-primary mb-2">Save</button>
            <a href="{{ route('campaign.index') }}" type="submit" class="btn btn-gradient-light btn-fw mb-2">Cancle</a>
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>

</div>

<!-- content-wrapper ends -->
@endsection
@push('scripts')
<script>
  // get the selected time for start time
  const hourSelect = document.getElementById('hourSelect');
  const minuteSelect = document.getElementById('minuteSelect');
  const combinedTimeInput = document.getElementById('combinedTime');
  hourSelect.addEventListener('change', updateCombinedTime);
  minuteSelect.addEventListener('change', updateCombinedTime);

  function updateCombinedTime() {
    const selectedHour = hourSelect.value;
    const selectedMinute = minuteSelect.value;
    const combinedTime = selectedHour + ':' + selectedMinute;

    combinedTimeInput.value = combinedTime;
  }

  // get the selected time for end time
  const endHourSelect = document.getElementById('endHourSelect');
  const endMinuteSelect = document.getElementById('endMinuteSelect');
  const combinedTimeInputEnd = document.getElementById('endCombinedTime');
  endHourSelect.addEventListener('change', updateEndCombinedTime);
  endMinuteSelect.addEventListener('change', updateEndCombinedTime);

  function updateEndCombinedTime() {
    const endSelectedHour = endHourSelect.value;
    const endSelectedMinute = endMinuteSelect.value;
    const endCombinedTime = endSelectedHour + ':' + endSelectedMinute;

    combinedTimeInputEnd.value = endCombinedTime;
  }
</script>
@endpush