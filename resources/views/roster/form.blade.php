@extends('include.layoutPopup')

@push('scripts')
<script>
function checkRoster(f){
    if( !$(f.name).val() ){
		swalAlert("이름를 입력해주세요.", "", "warning", "code");
		return false;
	}
}
</script>
@endpush

@section('content')
    <div class="win-popup-wrap popup-wrap type2" id="presentation-multi">
        <div class="popup-contents">
            <div class="popup-conbox">
                <div class="popup-contit-wrap">
                    <h2 class="popup-contit">개별 {{ $roster ? '수정' : '등록' }}</h2>
                </div>
                <div class="popup-con">
                    <form action="{{ route('roster.upsert', ['code'=>$code, 'sid'=>!empty($roster)?encrypt($roster['sid']):'']) }}" method="post" onsubmit="return checkRoster(this);">
                        {{ csrf_field() }}
                        <fieldset>
                            <legend class="hide">행사 등록</legend>
                            <ul class="write-wrap mt-15">
                                <li>
                                    <div class="form-tit">이름</div>
                                    <div class="form-con">
                                        <input type="text" name="name" id="name" value="{{ $roster->name ?? '' }}" class="form-item">
                                    </div>
                                </li>
                                <li>
                                    <div class="form-tit">소속</div>
                                    <div class="form-con">
                                        <input type="text" name="affiliation" id="affiliation" value="{{ $roster->affiliation ?? '' }}" class="form-item">
                                    </div>
                                </li>
                                <li>
                                    <div class="form-tit">이메일</div>
                                    <div class="form-con">
                                        <input type="text" name="email" id="email" value="{{ $roster->email ?? '' }}" class="form-item">
                                    </div>
                                </li>
                            </ul>

                            <div class="btn-wrap text-center mb-0">
                                <button type="submit" class="btn btn-type1 color-type4">{{ $roster ? '수정' : '저장' }}</button>
                                <button type="button" class="btn btn-type1 color-type6 colorClose">닫기</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection