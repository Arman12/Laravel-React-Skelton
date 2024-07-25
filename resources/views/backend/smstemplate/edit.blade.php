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
          <h4 class="card-title">Edit SMS Template</h4>
          @if(isset($smsTemplate))
          <form action="{{ route('sms.update', ['smsTemplate' => $smsTemplate]) }}" method="POST" class="form-sample">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Title</label>
                  <div class="col-sm-9">
                    <input type="text" name="title" value="{{ $smsTemplate->title }}" class="form-control" />
                    @error('title')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Subject</label>
                  <div class="col-sm-9">
                    <input type="text" name="subject" value="{{ $smsTemplate->subject }}"  class="form-control" />
                    @error('subject')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Dynamic fields</label>
                  <div class="col-sm-9">
                    <div class="tag-wrap">
                      <span class="tag tag-label">[[title]]</span>
                      <span class="tag tag-label">[[firstname]]</span>
                      <span class="tag tag-label">[[lastname]]</span>
                      <span class="tag tag-label">[[email]]</span>
                      <span class="tag tag-label">[[phone1]]</span>
                      <span class="tag tag-label">[[lookupId]]</span>
                      <span class="tag tag-label">[[customerName]]</span>
                      <span class="tag tag-label">[[LINK0]]</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Description</label>
                  <div class="col-sm-6">
                    <textarea name="description"  class="form-control"  rows="10">{{ $smsTemplate->description }}</textarea>
                    @error('description')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Status</label>
                  <div class="col-sm-4">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" id="status1" value="active" {{ $smsTemplate->status == 'active' ? 'checked' : '' }}> Active </label>
                    </div>
                  </div>
                  <div class="col-sm-5">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" id="status2" value="disable" {{ $smsTemplate->status == 'disable' ? 'checked' : '' }}> Disable </label>
                    </div>
                  </div>
                  @error('status')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-gradient-primary mb-2">Save</button>
            <a href="{{ route('sms.index') }}" type="submit" class="btn btn-gradient-light btn-fw mb-2">Cancle</a>
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>

</div>

<!-- content-wrapper ends -->
@endsection