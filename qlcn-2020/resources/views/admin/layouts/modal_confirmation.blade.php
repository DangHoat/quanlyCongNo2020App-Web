<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  {{-- <h4 class="modal-title" id="user_delete_confirm_title">@lang($model.'/modal.title')</h4> --}}
  <h4 class="modal-title" id="user_delete_confirm_title">{{trans('__title.users.delete')}}</h4>
</div>
<div class="modal-body">
    @if($error)
        <div>{!! $error !!}</div>
    @else
        {{-- @lang($model.'/modal.body') --}}
        {{trans('__title.users.content_alert_delete')}}
    @endif
</div>
<div class="modal-footer">
  {{-- <button type="button" class="btn btn-default" data-dismiss="modal">@lang($model.'/modal.cancel')</button> --}}
  <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('__title.layouts.cancel')}}</button>
  @if(!$error)
    <div class="btn_modal_confirm">
      <form id="commentForm" action="{{ $confirm_route }}" method="{{strtoupper($method) == 'GET' ? 'GET' : 'POST'}}" enctype="multipart/form-data" class="form-horizontal">
        {{-- <input type="submit" class="btn btn-danger" name="confirm" value="@lang($model.'/modal.confirm')"> --}}
        <input type="submit" class="btn btn-danger" name="confirm" value="{{trans('__title.layouts.delete')}}">
        {{ csrf_field() }}
        @if (strtoupper($method) != 'GET') {!! method_field($method) !!}
        @endif
      </form>
  </div>
  @endif
</div>