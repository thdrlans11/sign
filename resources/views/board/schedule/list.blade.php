@extends('include.layout')

@push('scripts')
<script>
function moveDate(){

	var year = $("#changeYear").val();
	var month = $("#changeMonth").val();

	location.href='/board/{{ $code }}/?year='+year+'&month='+month;

}	
</script>
@endpush

@section('content')
<div class="notice-wrap inner-layer">
	<!-- s:행사일정 Type A -->
	<div class="ev-wrap type1">
		<div class="ev-cal-list full">
			<div class="ev-cal-list-all flex jc-sb">
				<div class="ev-cal-list-form">
					<div class="form-group">
						<select class="form-item" id="changeYear" onchange="moveDate()">
							@for( $i=date('Y'); $i>=2020; $i-- )
							<option value="{{ $i }}" {{ $date['year'] == $i ? 'selected' : '' }}>{{ $i }}년</option>
							@endfor
						</select>
						<select class="form-item" id="changeMonth" onchange="moveDate()">
							<option value="">전체</option>
							@for( $i=1; $i<=12; $i++ )
							<option value="{{ sprintf('%02d',$i) }}" {{ $date['month'] == sprintf('%02d',$i) ? 'selected' : '' }}>{{ sprintf('%02d',$i) }}월</option>
							@endfor
						</select>
					</div>
					<div class="btn-wrap">
						<a href="{{ route('board.calendar', [ 'code'=>$code ]) }}" class="btn calendar-ic bg-type color5 cal">달력형 일정 확인</a>
						@if( in_array( ( auth('web')->check() ? auth('web')->user()->user_level : 'N' ) , config('site.board')[$code]['PermitPost'] ) )
						<a href="{{ route('board.form', $code ) }}" class="btn calendar-ic bg-type color4 add">일정 등록</a>
						@endif
					</div>
				</div>
				
				<div class="sch-wrap type3 font-pre">
					<form action="{{ route('board.list', $code) }}" method="get">
						<feildset>
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
						</feildset>
					</form>
				</div>
			</div>	
			<div class="ev-list-detail-guide-box">
				<p>* 행사명 클릭시 상세 내용을 확인하실 수 있습니다.</p>
			</div>
			<ul class="list-detail-all">
				@foreach( $lists as $list )
				<li>
					<a href="{{ route('board.view', ['code'=>$code, 'sid'=>base64_encode($list->sid)]) }}" title="상세보기">
						<div class="date">{{ $list->sdate->format('Y-m') }}<span class="ev-cate0{{ $list->category }}">{{ $list->sdate->format('d').( $list->date_type == 'L' ? ' ~ '.$list->edate->format('d') : '' ) }}</span></div>
						<div class="ev-list-detail">
							<p class="tit"><span class="ev-cate0{{ $list->category }}">[{{ config('site.board')[$code]['Category'][$list->category] }}]</span> {{ $list->subject }}</p>
							<p class="location">{{ $list->place }}</p>
						</div> 
					</a>
					@if( isAdminCheck() )
					<div class="has-btn flex jc-c ai-c">
						<a href="{{ route( 'board.form', ['code'=>$code, 'sid'=>base64_encode($list['sid'])] ) }}n" class="btn modify">수정</a>
						<a href="{{ route( 'board.delete', ['code'=>$code, 'sid'=>base64_encode($list['sid'])] ) }}" onclick="return confirm('정말 삭제하시겠습니까?')" class="btn del">삭제</a>
					</div>
					@endif
				</li>
				@endforeach
				
				@if( $lists->isEmpty() )
				<li class="no-data">
					<div class="date">{{ $date['year'] }}{{ $date['month'] ? '-'.$date['month'] : '' }}</div>
					<div class="ev-list-detail">
						<p class="tit no-cate">등록된 일정이 없습니다.</p>
					</div>
				</li>
				@endif
			</ul>
		</div>	

		{{ $lists->links('paginateUser', ['list'=>$lists]) }}

	</div>
</div>	
@endsection		
