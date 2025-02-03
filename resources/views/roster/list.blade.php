@extends('include.layout')

@push('scripts')
<script>
function dbChange(sid,db,field,f){

    var value = '';

	if( db == 'roster' && field == 'delete' ){
        value = $(f).data('status');
    }else{
        value = $(f).val();
    }

    $.ajax({
        type: 'POST',
        url: '{{ route('roster.dbChange', ['code'=>$event->code]) }}',
        data: { sid : sid, db : db, field : field, value : value },
        async: false,
        success: function(data) {
			if( data == 'error' ){
                swalAlert('시스템에러입니다.', '', 'error');
			}else{
				location.reload();
			}
        }
    });
}	
</script>
@endpush

@section('content')
<strong style="font-size:25px">{{ $event->title }}</strong>
<form action="{{ route('roster.list', ['code'=>$event->code]) }}" method="get" class="mb-30 mt-10">
    <fieldset>
        <legend class="hide">검색</legend>
        <div class="table-wrap">
            <table class="cst-table">
                <caption class="hide">
                    <colgroup>
                        <col style="width: 16.6%;">
                        <col style="width: 16.6%;">
                        <col style="width: 16.6%;">
                        <col style="width: 16.6%;">
                        <col style="width: 16.6%;">
                        <col style="width: 16.6%;">
                    </colgroup>
                    <tbody>
                        <tr>
                            <th scope="row">이름</th>
                            <td class="text-left">
                                <input type="text" name="name" id="name" value="{{ request()->query('name') }}" class="form-item">
                            </td>
                            <th scope="row">소속</th>
                            <td class="text-left">
                                <input type="text" name="affiliation" id="affiliation" value="{{ request()->query('affiliation') }}" class="form-item">
                            </td>
                            <th scope="row">이메일</th>
                            <td class="text-left">
                                <input type="text" name="email" id="email" value="{{ request()->query('email') }}" class="form-item">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">메모</th>
                            <td class="text-left">
                                <div class="form-con">
                                    <div class="radio-wrap cst">
                                        @foreach( config('site.common.selectYn') as $key => $val )
                                        <label for="memoYN{{ $key }}" class="radio-group">
                                            <input type="radio" name="memoYN" id="memoYN{{ $key }}" value="{{ $key }}" {{ ( request()->query('memoYN') ?? '' ) == $key ? 'checked' : '' }}>{{ $val }}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                            <th scope="row">서명상태</th>
                            <td class="text-left">
                                <div class="form-con">
                                    <div class="radio-wrap cst">
                                        @foreach( config('site.common.selectYn') as $key => $val )
                                        <label for="signYN{{ $key }}" class="radio-group">
                                            <input type="radio" name="signYN" id="signYN{{ $key }}" value="{{ $key }}" {{ ( request()->query('signYN') ?? '' ) == $key ? 'checked' : '' }}>{{ $val }}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                            <th scope="row"></th>
                            <td class="text-left">                                
                            </td>
                        </tr>
                        
                    </tbody>
                </caption>
            </table>
        </div>
        
        <div class="btn-wrap text-center">
            <button type="submit" class="btn btn-type1 color-type4">검색</button>
            <button type="reset" class="btn btn-type1 color-type6" onclick="location.href='{{ route('roster.list', ['code'=>$event->code]) }}'">검색 초기화</button>
            <a href="{{ route('download', ['type'=>'zip', 'tbl'=>'event', 'sid'=>encrypt($event->sid), 'kind'=>'fee']) }}" class="btn btn-type1 color-type10" target="_blank">연자료지급 확인서 일괄 다운로드</a>
            <a href="{{ route('download', ['type'=>'zip', 'tbl'=>'event', 'sid'=>encrypt($event->sid), 'kind'=>'agree']) }}" class="btn btn-type1 color-type11" target="_blank">강의자료제공 동의서 일괄 다운로드</a>
        </div>
    </fieldset>
</form>

<div class="list-contop text-right cf">
    <span class="cnt full-left pt-10">
        [총 <strong>{{ $lists->total() }}</strong>명]
    </span>
    <div class="list-contop text-right cf">
        <a href="{{ route('roster.fileForm', ['code'=>$event->code, 'esid'=>encrypt($event->sid)]) }}" class="btn btn-small color-type12 Load_Base_fix" wsize="960" hsize="530" tsize="4%" reload="Y">파일업로드</a>
        <a href="{{ route('roster.excelUploadForm', ['code'=>$event->code]) }}" class="btn btn-small color-type10 Load_Base_fix" Wsize="730" Hsize="1000" Tsize="0%" Reload="Y">명단 일괄업로드</a>
        <a href="{{ route('roster.form', ['code'=>$event->code]) }}" class="btn btn-small color-type13 Load_Base_fix" wsize="960" hsize="480" tsize="4%" reload="Y">개별등록</a>
    </div>
</div>

<div class="table-wrap">
    <table class="cst-table list-table">
        <caption class="hide">목록</caption>
        <colgroup>
            <col style="width: 5%;">
            <col style="width: auto">
            <col style="width: auto;">
            <col style="width: auto;">
            <col style="width: 12%;">
            <col style="width: 12%;">
            <col style="width: 6%;">
            <col style="width: 6%;">
        </colgroup>
        <thead>
            <tr>
                <th scope="col" rowspan="2">No</th>
                <th scope="col" rowspan="2">이름</th>
                <th scope="col" rowspan="2">소속</th>
                <th scope="col" rowspan="2">이메일</th>
                <th scope="col" colspan="2">서명상태</th>
                <th scope="col" rowspan="2">메모</th>
                <th scope="col" rowspan="2">관리</th>
            </tr>
            <tr><th scope="col">연자료지급 확인서</th>
                <th scope="col">강의자료제공 동의서</th>

            </tr>
        </thead>
        <tbody>
            @foreach( $lists as $index => $d )
            <tr>
                <td>{{ $d->seq }}</td>
                <td>{{ $d->name }}</td>
                <td>{{ $d->affiliation }}</td>
                <td>{{ $d->email }}</td>
                <td>
                    @if( $d->realfile_fee )
                    <a href="{{ route('download', ['type'=>'only', 'tbl'=>'rosters', 'sid'=>encrypt($d->sid), 'kind'=>'fee']) }}" class="btn btn-small color-type12">다운로드</a>
                    <a href="#n" style="position: relative; top:6px" onclick="swalConfirm('서명파일을 삭제하시겠습니까?', '', function(){ dbChange('{{ encrypt($d->sid) }}','roster','fee', $('.btn-del')); })">
                        <span class="material-symbols-outlined">
                        delete
                        </span>
                    </a>  
                    <br>
                    <span style="color:#4646e7">{{ $d->fee_upload_date }}</span>                                      
                    @endif
                </td>
                <td>
                    @if( $d->realfile_agree )
                    <a href="{{ route('download', ['type'=>'only', 'tbl'=>'rosters', 'sid'=>encrypt($d->sid), 'kind'=>'agree']) }}" class="btn btn-small color-type12">다운로드</a>
                    <a href="" style="position: relative; top:6px">
                        <span class="material-symbols-outlined">
                        delete
                        </span>
                    </a>
                    <br>
                    <span style="color:#4646e7">{{ $d->agree_upload_date }}</span>                    
                    @endif
                </td>
                <td>                    
                    <a href="{{ route('roster.memoForm', ['code'=>$event->code, 'sid'=>encrypt($d->sid)]) }}" class="Load_Base_fix" Wsize="730" Hsize="740" Tsize="2%" Reload="Y">
                        <span class="material-symbols-outlined" {!! $d->memo ? 'style="color:#c94848"' : '' !!}>
                            content_paste{{ !$d->memo ? '_off' : ''}}
                        </span>
                    </a>
                </td>
                <td>
                    <a href="{{ route('roster.form', ['code'=>$event->code, 'sid'=>encrypt($d->sid)]) }}" class="btn-admin btn-modify Load_Base_fix" wsize="960" hsize="480" tsize="4%" Reload="Y"><img src="/devAdmin/image/admin/ic_modify.png" alt="수정"></a>
                    <a href="#n" class="btn-admin btn-del" onclick="swalConfirm('삭제 처리하시겠습니까?', '', function(){ dbChange('{{ encrypt($d->sid) }}','roster','delete',$('.btn-del')); })" data-status="Y"><img src="/devAdmin/image/admin/ic_del.png" alt="삭제"></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $lists->links('paginateUser', ['list'=>$lists]) }}

</div>
@endsection   
