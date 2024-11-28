@extends('include.layout')

@section('content')
<div class="notice-wrap inner-layer">
	<div class="ev-wrap type1">
		<div class="sub-contit-box">
			<p class="sub-cont-tit">교육일정</p>
		</div>
		<div class="write-form-wrap">
			<ul class="write-wrap mt0">
				@if( config('site.board')[$code]['UseCategory'] && $data['thread'] == 'A' )
				<li>
					<div class="form-tit">행사 구분</div>
					<div class="form-con">
						{{ config('site.board')[$code]['Category'][$data->category] }}
					</div>
				</li>
				@endif
				<li>
					<div class="form-tit">교육명</div>
					<div class="form-con">
						{{ $data->subject }}
					</div>
				</li>
				<li>
					<div class="form-tit">교육일</div>
					<div class="form-con">
						{{ $data->sdate->toDateString().( $data->date_type == 'L' ?' ~ '.$data->edate->toDateString():'' ) }}
					</div>
				</li>
				<li>
					<div class="form-tit">장소</div>
					<div class="form-con">
						{{ $data->place }}
					</div>
				</li>
				<li>
					<div class="form-tit">주최</div>
					<div class="form-con">
						{{ $data->sponsor }}
					</div>
				</li>
				<li>
					<div class="form-tit">문의처</div>
					<div class="form-con">
						{{ $data->inquiry }}
					</div>
				</li>
				@if( config('site.board')[$code]['UseCategory'] && $data->linkurl )
				<li>
					<div class="form-tit">홈페이지</div>
					<div class="form-con">
						<a href="{{ $data->linkurl }}" target="_blank">{{ $data->linkurl }}</a>
					</div>
				</li>
				@endif
				<li>
					<div class="form-tit">내용</div>
					<div class="form-con">
						{!! $data['content'] !!}
					</div>
				</li>
				@if( config('site.board')[$code]['UseFile'] && $files->isNotEmpty() )
				<li>
					<div class="form-tit">첨부파일</div>
					<div class="form-con">
						<div class="view-attach-con">
							<div class="con">
								@foreach( $files as $key => $f )
								<a href="{{ route('download', ['type'=>'only', 'tbl'=>'boardFile', 'sid'=>base64_encode($f['sid'])]) }}" target="_blank">{{ $f['filename'] }} (다운로드 {{ $f['download'] }}회)</a><br>
								@endforeach
							</div>
						</div>
					</div>
				</li>
				@endif
			</ul>

			<div class="btn-wrap jc-e">
				<a href="{{ route('board.list', $code) }}" class="btn calendar line-type color6">목록</a>
				@if( auth('web')->check() && (auth('web')->user()->isAdmin() || $data->id ==  auth('web')->user()->user_id) )
				<a href="{{ route('board.form', ['code'=>$code, 'sid'=>base64_encode($data->sid)]) }}" class="btn calendar bg-type color4">수정</a>
				<a href="{{ route('board.delete', ['code'=>$code, 'sid'=>base64_encode($data->sid)]) }}" onclick="return confirm('정말 삭제하시겠습니까?')" class="btn calendar bg-type color6">삭제</a>
				@endif
			</div>
		</div>
	</div>
</div>	
@endsection        
