@extends('include.layout')

@push('scripts')
<script>
function dbChange(sid,field,value){
    $.ajax({
        type: 'POST',
        url: '{{ route('board.dbChange', ['code'=>$code]) }}',
        data: { sid : sid, field : field, value : value },
        async: false,
        success: function(data) {
            if( data == 'error' ){
                alert('시스템에러입니다.');
            }else{
                location.reload();
            }
        }
    });
}
</script>
@endpush

@section('content')
<div class="notice-wrap inner-layer">
	<link href="/assets/css/board.css" rel="stylesheet">
	<div id="board" class="board-wrap notice">

		<div class="sch-wrap type3 skin1 font-pre">
			<form action="{{ route('board.list', $code) }}" method="get">
				<fieldset>
					<legend class="hide">검색</legend>
					<div class="form-group">
						<select name="keyfield" id="keyfield" class="form-item sch-cate">
							@foreach( config('site.board')[$code]['Search'] as $key => $val )
							<option value="{{ $key }}" @if( request()->query('keyfield') == $key ) selected @endif>{{ $val }}</option>
							@endforeach
						</select>
						<input type="text" name="keyword" id="keyword" value="{{ request()->query('keyword') }}" class="form-item sch-key" placeholder="검색어를 입력하세요.">
						<button type="submit" class="btn btn-sch"><span class="hide">검색</span></button>
					</div>	
				</fieldset>
			</form>
		</div>

		<ul class="board-list">

			<li class="list-head">
				<div class="bbs-no bbs-col-xs n-bar">번호</div>
				<div class="bbs-tit n-bar">제목</div>
				<div class="bbs-file bbs-col-xs">파일</div>
				<div class="bbs-cate bbs-col-s n-bar">조회수</div>
				<div class="bbs-name bbs-col-m">작성자</div>
				<div class="bbs-date bbs-col-m">작성일</div>
				@if( isAdminCheck() )
				<div class="bbs-show bbs-col-s">공개여부</div>
				<div class="bbs-admin bbs-col-s">관리</div>
				@endif
			</li>

			@foreach( $notice as $board )
			<li class="active">
				<div class="bbs-no bbs-col-xs n-bar">
					<img src="/assets/image/board/ic_notice.png" alt="공지" class="ic-notice">
				</div>
				<div class="bbs-tit n-bar">
					<a href="{{ route('board.view', ['code'=>$code, 'sid'=>base64_encode($board->sid)]) }}" class="ellipsis">{{ $board->subject }}</a>
					@if( $board->created_at->copy()->addDay(1) > now() )
					<span class="ic-new">N</span>
					@endif
				</div>
				
				<div class="bbs-file bbs-col-xs">
					@if( $board['files_count'] > 0 )
						<img src="/assets/image/board/ic_attach_file.png" alt="">
					@endif
				</div>
				
				<div class="bbs-hit bbs-col-s n-bar">{{ $board->ref }}</div>
				<div class="bbs-date bbs-col-m">{{ $board->name }}</div>  
				<div class="bbs-name bbs-col-m">{{ $board->created_at->toDateString() }}</div>
				@if( isAdminCheck() )
				<div class="bbs-show bbs-col-s">
					<select class="form-item" onchange="dbChange('{{ base64_encode($board->sid) }}', 'hide', $(this).val())">
						@foreach( config('site.board.select.hide') as $key => $val )
						<option value="{{ $key }}" {{ ( $board->hide ?? '' ) == $key ? 'selected' : '' }}>{{ $val }}</option>
						@endforeach
					</select>
				</div>
				<div class="bbs-admin bbs-col-s">
					<div class="btn-admin">
						<a href="{{ route( 'board.form', ['code'=>$code, 'sid'=>base64_encode($board['sid'])] ) }}" class="btn btn-modify"><span class="hide">수정</span></a>
						<a href="{{ route( 'board.delete', ['code'=>$code, 'sid'=>base64_encode($board['sid'])] ) }}" class="btn btn-delete" onclick="return confirm('정말 삭제하시겠습니까?')"><span class="hide">삭제</span></a>
					</div>
				</div>
				@endif
			</li>
			@endforeach
			
			@foreach( $lists as $index => $board )	
			<li>
				<div class="bbs-no bbs-col-xs n-bar">
					{{ $board->seq }}
				</div>
				<div class="bbs-tit n-bar">
					<a href="{{ route('board.view', ['code'=>$code, 'sid'=>base64_encode($board->sid)]) }}" class="ellipsis">{{ $board->subject }}</a>
					@if( $board->created_at->copy()->addDay(1) > now() )
					<span class="ic-new">N</span>
					@endif
				</div>
				<div class="bbs-file bbs-col-xs">
					@if( $board['files_count'] > 0 )
					<img src="/assets/image/board/ic_attach_file.png" alt="">
					@endif
				</div>
				<div class="bbs-hit bbs-col-s n-bar">{{ $board->ref }}</div>
				<div class="bbs-date bbs-col-m">{{ $board->name }}</div>  
				<div class="bbs-name bbs-col-m">{{ $board->created_at->toDateString() }}</div>
				@if( isAdminCheck() )
				<div class="bbs-show bbs-col-s">
					<select class="form-item" onchange="dbChange('{{ base64_encode($board->sid) }}','hide', $(this).val() );">
						@foreach( config('site.board.select.hide') as $key => $val )
						<option value="{{ $key }}" {{ ( $board->hide ?? '' ) == $key ? 'selected' : '' }}>{{ $val }}</option>
						@endforeach
					</select>
				</div>
				<div class="bbs-admin bbs-col-s">
					<div class="btn-admin">
						<a href="{{ route( 'board.form', ['code'=>$code, 'sid'=>base64_encode($board['sid'])] ) }}" class="btn btn-modify"><span class="hide">수정</span></a>
						<a href="{{ route( 'board.delete', ['code'=>$code, 'sid'=>base64_encode($board['sid'])] ) }}" class="btn btn-delete" onclick="return confirm('정말 삭제하시겠습니까?')"><span class="hide">삭제</span></a>
					</div>
				</div>
				@endif
			</li>
			@endforeach
			
			@if( $notice->isEmpty() && $lists->isEmpty() )
			<li class="no-data text-center">
				등록된 게시글이 없습니다.
			</li>
			@endif
		</ul>

		@if( in_array( ( auth('web')->check() ? auth('web')->user()->user_level : 'N' ) , config('site.board')[$code]['PermitPost'] ) )
		<div class="btn-wrap jc-e">
			<a href="{{ route('board.form', $code ) }}" class="btn calendar bg-type color5">등록</a>
		</div>
		@endif

		{{ $lists->links('paginateUser', ['list'=>$lists]) }}

	</div>
</div>



@endsection		
