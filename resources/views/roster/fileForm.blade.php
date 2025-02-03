@extends('include.layoutPopup')

@push('scripts')
<script>
function checkFile(f){
    if( $("#delfile").length > 0 ){
        if( $(f.userfile).val() == "" && $("#delfile").is(":checked") ){
            swalAlert("연자료지급 확인서를 첨부해주세요.", "", "warning", "userfile");
            return false;
        }
    }else{
        if( $(f.userfile).val() == "" ){
            swalAlert("연자료지급 확인서를 첨부해주세요.", "", "warning", "userfile");
            return false;
        }
    }
}
</script>
@endpush

@section('content')
    <div class="win-popup-wrap popup-wrap type2" id="presentation-multi">
        <div class="popup-contents">
            <div class="popup-conbox">
                <div class="popup-contit-wrap">
                    <h2 class="popup-contit">파일 업로드</h2>
                </div>
                <div class="popup-con">
                    <form action="{{ route('roster.fileUpsert', ['code'=>$code, 'esid'=>encrypt($event->sid)]) }}" method="post" enctype="multipart/form-data" onsubmit="return checkFile(this);">
                        {{ csrf_field() }}
                        <fieldset>
                            <legend class="hide">행사 등록</legend>
                            <ul class="write-wrap mt-15">
                                <li>
                                    <div class="form-tit">연자료지급 확인서</div>
                                    <div class="form-con">
                                        <div class="filebox">
                                            <input class="upload-name form-item" id="filenameText" placeholder="파일첨부" readonly="readonly">
                                            <label for="userfile" class="btn btn-small" style="width:107px; margin-top:0px">파일선택</label> 
                                            <input type="file" name="userfile" id="userfile" class="file-upload" onclick="return file_check('{{ $event['filename_fee'] ?? '' }}','delfile')" onchange="fileAcceptCheck(this, 'filenameText', '');" accept=".jpg, .png, .gif">  
                                            @if( isset($event->realfile_fee) )
                                            <div class="attach-file">
                                                <a href="{{ route('download', ['type'=>'only', 'tbl'=>'events', 'sid'=>encrypt($event->sid), 'kind'=>'fee']) }}">{{ $event['filename_fee'] ?? '' }}</a>
                                                <input type="checkbox" name="delfile" id="delfile" value="{{ $event->realfile_fee ?? '' }}" style="position: relative; top:3px; left:5px"/><span class="file-link pl-10"></span>
                                            </div>
                                            <span class="text-red" style="position: relative; top:4px">체크 후 수정 시 파일이 삭제됩니다.</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-tit">강의자료제공 동의서</div>
                                    <div class="form-con">
                                        <div class="filebox">
                                            <input class="upload-name form-item" id="filenameText2" placeholder="파일첨부" readonly="readonly">
                                            <label for="userfile2" class="btn btn-small" style="width:107px; margin-top:0px">파일선택</label> 
                                            <input type="file" name="userfile2" id="userfile2" class="file-upload" onclick="return file_check('{{ $event['filename_agree'] ?? '' }}','delfile2')" onchange="fileAcceptCheck(this, 'filenameText2', '');" accept=".jpg, .png, .gif">  
                                            @if( isset($event->realfile_agree) )
                                            <div class="attach-file">
                                                <a href="{{ route('download', ['type'=>'only', 'tbl'=>'events', 'sid'=>encrypt($event->sid), 'kind'=>'agree']) }}">{{ $event['filename_agree'] ?? '' }}</a>
                                                <input type="checkbox" name="delfile2" id="delfile2" value="{{ $event->realfile_agree ?? '' }}" style="position: relative; top:3px; left:5px"/><span class="file-link pl-10"></span>
                                            </div>
                                            <span class="text-red" style="position: relative; top:4px">체크 후 수정 시 파일이 삭제됩니다.</span>
                                            @endif
                                        </div>
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