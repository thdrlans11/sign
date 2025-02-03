@extends('include.layoutPopup')

@push('scripts')
<script>
function checkEvent(f){
    if( !$(f.code).val() ){
		swalAlert("행사코드(아이디)를 입력해주세요.", "", "warning", "code");
		return false;
	}
	if( !$(f.company).val() ){
		swalAlert("학회명을 입력해주세요.", "", "warning", "company");
		return false;
	}
    if( !$(f.title).val() ){
		swalAlert("행사명을 입력해주세요.", "", "warning", "title");
		return false;
	}
    if( $(f.sdate).val() == "" || $(f.edate).val() == "" ){
        swalAlert("행사일자를 설정해주세요.","","warning","sdate");
        return false;
    }
    if( !$(f.code).val() ){
		swalAlert("행사코드(아이디)를 입력해주세요.", "", "warning", "code");
		return false;
	}
    @if( !$event )
    if( !$(f.password).val() ){
		swalAlert("비밀번호를 입력해주세요.", "", "warning", "password");
		return false;
	}
    @endif
    if( !$(f.manager).val() ){
		swalAlert("담당자를 입력해주세요.", "", "warning", "manager");
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
                    <h2 class="popup-contit">행사 {{ $event ? '수정' : '등록' }}</h2>
                </div>
                <div class="popup-con">
                    <form action="{{ route('event.upsert', ['sid'=>!empty($event)?encrypt($event['sid']):'']) }}" method="post" onsubmit="return checkEvent(this);">
                        {{ csrf_field() }}
                        <fieldset>
                            <legend class="hide">행사 등록</legend>
                            <ul class="write-wrap mt-15">
                                <li>
                                    <div class="form-tit">학회명</div>
                                    <div class="form-con">
                                        <input type="text" name="company" id="company" value="{{ $event->company ?? '' }}" class="form-item datepicker" placeholder="학회명을 입력해주세요.">
                                    </div>
                                </li>
                                <li>
                                    <div class="form-tit">행사명</div>
                                    <div class="form-con">
                                        <input type="text" name="title" id="title" value="{{ $event->title ?? '' }}" class="form-item datepicker" placeholder="행사명을 입력해주세요.">
                                    </div>
                                </li>
                                <li>
                                    <div class="form-tit">행사일자</div>
                                    <div class="form-con">
                                        <div class="form-group form-group-text">
                                            <input type="text" name="sdate" id="sdate" value="{{ $event->sdate ?? '' }}" class="form-item datepicker dateCalendar w-30p" readonly>
                                            <span class="text">-</span>
                                            <input type="text" name="edate" id="edate" value="{{ $event->edate ?? '' }}" class="form-item datepicker dateCalendar w-30p" readonly>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-tit">행사코드 (아이디)</div>
                                    <div class="form-con">
                                        <input type="text" name="code" id="code" value="{{ $event->code ?? '' }}" class="form-item" placeholder="행사코드(아이디)를 입력해주세요.">
                                    </div>
                                </li>
                                <li>
                                    <div class="form-tit">비밀번호</div>
                                    <div class="form-con">
                                        <input type="password" name="password" id="password" class="form-item" placeholder="비밀번호를 입력해주세요.">
                                    </div>
                                </li>
                                <li>
                                    <div class="form-tit">담당자</div>
                                    <div class="form-con">
                                        <input type="text" name="manager" id="manager" value="{{ $event->manager ?? '' }}" class="form-item" placeholder="담당자를 입력해주세요.">
                                    </div>
                                </li>
                                
                            </ul>

                            <div class="btn-wrap text-center mb-0">
                                <button type="submit" class="btn btn-type1 color-type4">{{ $event ? '수정' : '저장' }}</button>
                                <button type="button" class="btn btn-type1 color-type6 colorClose">닫기</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection