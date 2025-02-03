@extends('include.layoutPopup')

@section('content')
<div class="win-popup-wrap popup-wrap type2" id="presentation-multi">
    <div class="popup-contents">
        <div class="popup-conbox">
            <div class="popup-contit-wrap">
                <h2 class="popup-contit">메모 등록</h2>
            </div>
            <div class="popup-con">
                <form action="{{ route('roster.memo', ['code'=>$code, 'sid'=>encrypt($roster->sid)]) }}" method="post">
                    {{ csrf_field() }}
                    <fieldset>
                        <legend class="hide">Memo</legend>

                        <ul class="write-wrap">
                            <li>
                                <div class="form-con">
                                    <textarea class="form-item" style="height:400px" name="memo">{{ $roster->memo ?? '' }}</textarea>
                                </div>
                            </li>
                        </ul>    

                        <div class="btn-wrap text-center mb-0">
                            <button type="submit" class="btn btn-type1 color-type4">저장</button>
                            <button type="button" class="btn btn-type1 color-type6 colorClose">닫기</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>        
    </div>    
</div>
@endsection
