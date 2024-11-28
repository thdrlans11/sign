@extends('include.layout')

@section('content')

<div class="notice-wrap inner-layer">
	<!-- s:board -->             
	<link href="/assets/css/board.css" rel="stylesheet">
	<link href="/assets/css/editor.css" rel="stylesheet">

	<div class="board-view">

		<div class="view-contop">
			<h4 class="view-tit">
				{{ $data->subject }}
			</h4>
			<div class="view-info text-right">
				<span><strong>조회수 : </strong>{{ $data->ref }}</span>
				<span><strong>게시일 : </strong>{{ $data->created_at->toDateString() }}</span>
			</div>
		</div>
		@if( config('site.board')[$code]['UseCategory'] && $data->linkurl )
		<div class="view-link text-right">
			<a href="{{ $data->linkurl }}" target="_blank">{{ $data->linkurl }}</a>
		</div>
		@endif		
		<div class="view-contents editor-contents">
			{!! $data['content'] !!}	
		</div>
		@if( config('site.board')[$code]['UseFile'] && $files->isNotEmpty() )
		<div class="view-attach">
			<div class="view-attach-con">
				<div class="con">
					@foreach( $files as $key => $f )
					<a href="{{ route('download', ['type'=>'only', 'tbl'=>'boardFile', 'sid'=>base64_encode($f['sid'])]) }}" target="_blank">{{ $f['filename'] }} (다운로드 {{ $f['download'] }}회)</a>
					@endforeach
				</div>
			</div>
		</div>
		@endif

		<div class="btn-wrap jc-e">
			<a href="{{ route('board.list', $code) }}" class="btn calendar line-type color6">목록</a>
			@if( isAdminCheck() || ( auth('web')->check() && $data->id == auth('web')->user()->user_id ) )
			<a href="{{ route('board.form', ['code'=>$code, 'sid'=>base64_encode($data->sid)]) }}" class="btn calendar bg-type color4">수정</a>
			<a href="{{ route('board.delete', ['code'=>$code, 'sid'=>base64_encode($data->sid)]) }}" class="btn calendar bg-type color6">삭제</a>
			@endif
		</div>
	</div>
</div>
@endsection        
