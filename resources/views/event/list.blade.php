@extends('include.layout')

@push('scripts')
<script>
function dbChange(sid,db,field,f){

    var value = '';

	if( db == 'event' && field == 'delete' ){
        value = $(f).data('status');
    }else{
        value = $(f).val();
    }

    $.ajax({
        type: 'POST',
        url: '{{ route('event.dbChange') }}',
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

<form action="{{ route('event.list') }}" method="get" class="mb-30">
    <fieldset>
        <legend class="hide">검색</legend>
        <div class="table-wrap">
            <table class="cst-table">
                <caption class="hide">
                    <colgroup>
                        <col style="width: 18%;">
                        <col style="width: 32%;">
                        <col style="width: 18%;">
                        <col style="width: 32%;">
                    </colgroup>
                    <tbody>
                        <tr>
                            <th scope="row">행사명</th>
                            <td class="text-left">
                                <input type="text" name="title" id="title" value="{{ request()->query('title') }}" class="form-item">
                            </td>
                            <th scope="row">학회명</th>
                            <td class="text-left">
                                <input type="text" name="company" id="company" value="{{ request()->query('company') }}" class="form-item">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">담당자</th>
                            <td class="text-left">
                                <input type="text" name="manager" id="manager" value="{{ request()->query('manager') }}" class="form-item">
                            </td>
                            <th scope="row">행사코드 (아이디)</th>
                            <td class="text-left">
                                <input type="text" name="code" id="code" value="{{ request()->query('code') }}" class="form-item">
                            </td>
                        </tr>
                        
                    </tbody>
                </caption>
            </table>
        </div>
        
        <div class="btn-wrap text-center">
            <button type="submit" class="btn btn-type1 color-type4">검색</button>
            <button type="reset" class="btn btn-type1 color-type6" onclick="location.href='{{ route('event.list') }}'">검색 초기화</button>
            {{-- <a href="{{ route('event.excel', request()->except('page')) }}" class="btn btn-type1 color-type10" target="_blank">엑셀 다운로드</a> --}}
        </div>
    </fieldset>
</form>

<div class="list-contop text-right cf">
    <span class="cnt full-left pt-10">
        [총 <strong>{{ $lists->total() }}</strong>건]
    </span>
    <a href="{{ route('event.form') }}" class="btn btn-small color-type13 Load_Base_fix" wsize="960" hsize="660" tsize="4%" reload="Y">행사 등록</a>
</div>

<div class="table-wrap">
    <table class="cst-table list-table">
        <caption class="hide">목록</caption>
        <colgroup>
            <col style="width: 15%;">
            <col style="width: *">
            <col style="width: 10%;">
            <col style="width: 15%;">
            <col style="width: 8%;">
            <col style="width: 8%;">
            <col style="width: 8%;">
            <col style="width: 8%;">
        </colgroup>
        <thead>
            <tr>
                <th scope="col">학회명</th>
                <th scope="col">행사명</th>
                <th scope="col">담당자</th>
                <th scope="col">행사일자</th>
                <th scope="col">행사코드</th>
                <th scope="col">명단관리</th>
                <th scope="col">데이터초기화</th>
                <th scope="col">관리</th>
            </tr>
        </thead>
        <tbody>
            @foreach( $lists as $index => $d )
            <tr>
                <td>{{ $d->company }}</td>
                <td>{{ $d->title }}</td>
                <td>{{ $d->manager }}</td>
                <td>{{ $d->sdate.' ~ '.$d->edate }}</td>
                <td>{{ $d->code }}</td>
                <td>
                    <a href="{{ route('roster.list', ['code'=>$d->code]) }}" class="btn btn-small color-type7">명단관리</a>
                </td>
                <td>
                    <a href="#n" onclick="swalConfirm('초기화 하시겠습니까?<br>서명 받은 파일이 모두 삭제됩니다.', '', function(){ dbChange('{{ encrypt($d->sid) }}','event','reset',$('.btn-del')); })" class="btn btn-small color-type4">초기화</a>
                </td>
                <td>
                    <a href="{{ route('event.form', ['sid'=>encrypt($d->sid)]) }}" class="btn-admin btn-modify Load_Base_fix" wsize="960" hsize="660" tsize="4%" Reload="Y"><img src="/devAdmin/image/admin/ic_modify.png" alt="수정"></a>
                    <a href="#n" class="btn-admin btn-del" onclick="swalConfirm('삭제 처리하시겠습니까?', '', function(){ dbChange('{{ encrypt($d->sid) }}','event','delete',$('.btn-del')); })" data-status="Y"><img src="/devAdmin/image/admin/ic_del.png" alt="삭제"></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $lists->links('paginateUser', ['list'=>$lists]) }}

</div>
@endsection   
