@extends('include.layout')			

@push('scripts')
<script>
function moveDate(this_day = null){

	var year = $("#changeYear").val();
	var month = $("#changeMonth").val();
	var makeDate = year+'-'+month+'-01';

	if( this_day ){
		location.href='/board/{{ $code }}/calendar?search_date='+makeDate+'&this_day='+this_day;
	}else{
		location.href='/board/{{ $code }}/calendar?search_date='+makeDate;
	}
	

}	
</script>
@endpush

@section('content')
<?
$maxDay = date("t", strtotime($date['start_date'])); //총 일수
$startDay = date("w", strtotime($date['start_date'])); // 1일은 무슨요일인가
$endDay = date("w", strtotime($date['end_date'])); // 마지막일은 무슨요일인가
$maxWeek = ceil(($maxDay+$startDay)/7); // 총주수
$day = 1;
?>

<div class="notice-wrap inner-layer">
	<div class="ev-wrap type1">
		<div class="ev-cal-wrap">
			<div class="ev-cal">
				<div class="ev-contop">
					<div class="ev-year">
						<a href="{{ route('board.calendar', ['code'=>$code, 'search_date'=>$date['prev_date']]) }}" class="btn btn-year btn-year-prev" title="이전"><span class="hide">이전</span></a>
						<strong class="year">{{ $date['year'] }}.{{ $date['month'] }}</strong>
						<a href="{{ route('board.calendar', ['code'=>$code, 'search_date'=>$date['next_date']]) }}" class="btn btn-year btn-year-next" title="다음"><span class="hide">다음</span></a>
					</div>
					<div class="ev-cate-guide">
						<p class="ev-cate ev-cate01">국내</p>
						<p class="ev-cate ev-cate02">국외</p>
					</div>
				</div>

				<table class="cal-table">
					<caption class="hide">달력</caption>
					<colgroup>
						<col style="width: 14.2%;">
						<col style="width: 14.2%;">
						<col style="width: 14.2%;">
						<col style="width: 14.2%;">
						<col style="width: 14.2%;">
						<col style="width: 14.2%;">
						<col style="width: 14.2%;">
					</colgroup>
					<thead>
						<tr>
							<th scope="col" class="sun">SUN</th>
							<th scope="col">MON</th>
							<th scope="col">TUE</th>
							<th scope="col">WED</th>
							<th scope="col">THU</th>
							<th scope="col">FRI</th>
							<th scope="col" class="sat">SAT</th>
						</tr>
					</thead>
					<tbody>		
						@for( $i=1; $i<=$maxWeek; $i++ )
						<tr>
							@for( $j=0; $j<7; $j++ )
							<td class="@if( $j == 0 ) sun @elseif( $j == 6 ) sat @endif">
								@if( ( $i==1 && $j < $startDay ) || ( $i==$maxWeek && $j > $endDay ) )
									
								@else
									<? 
									$s=1;
									$this_day = $date['year'].'-'.$date['month'].'-'.sprintf('%02d',$day); 
									?>
									<a href="" onclick="moveDate('{{ $this_day }}'); return false;">
										<span class="num">{{ $day }}</span>
										
										@foreach( $calendars[$this_day] as $calendar ) @if( $s > 2 ) @continue; @endif										

										<?
										$boardCount = \App\Models\Board::where('code', $code)->where('category',$calendar['category'])
																		->whereRaw('substr(sdate,1,10) <= \''.$this_day.'\'')
																		->where(function ($query) use($this_day) {
																		$query->whereDate('sdate',$this_day)->orWhereRaw('substr(edate,1,10) >= \''.$this_day.'\''); 
																		})->count();
										?>

										<div class="ev-cate-wrap">
											<span class="ev-cate ev-cate0{{ $calendar['category'] }}">
												{{ $boardCount > 1 ? '( '.$boardCount.' )' : '' }}
											</span>
										</div>
										<? $s++ ?>										
										@endforeach					
									</a>				
									<? $day++; ?>
								@endif	
							</td>
							@endfor
						</tr>
						@endfor
					</tbody>
				</table>
			</div>
			<div class="ev-cal-list">
				<div class="ev-cal-list-form">
					<div class="form-group">
						<select class="form-item" id="changeYear" onchange="moveDate()">
							@for( $i=date('Y'); $i>=2020; $i-- )
							<option value="{{ $i }}" {{ $date['year'] == $i ? 'selected' : '' }}>{{ $i }}년</option>
							@endfor
						</select>
						<select class="form-item" id="changeMonth" onchange="moveDate()">
							@for( $i=1; $i<=12; $i++ )
							<option value="{{ sprintf('%02d',$i) }}" {{ $date['month'] == sprintf('%02d',$i) ? 'selected' : '' }}>{{ sprintf('%02d',$i) }}월</option>
							@endfor
						</select>
					</div>
					<div class="btn-wrap">
						<a href="{{ route('board.list', [ 'code'=>$code ]) }}" class="btn calendar-ic bg-type color5 cal">전체일정 확인</a>
						@if( in_array( ( auth('web')->check() ? auth('web')->user()->user_level : 'N' ) , config('site.board')[$code]['PermitPost'] ) )
						<a href="{{ route('board.form', $code ) }}" class="btn calendar-ic bg-type color4 add">일정 등록</a>
						@endif
					</div>
				</div>
				<ul>
					@foreach( $lists as $list )
					<li>
						<div class="date">{{ $list->sdate->format('Y-m') }}<span class="ev-cate0{{ $list->category }}">{{ $list->sdate->format('d').( $list->date_type == 'L' ? ' ~ '.$list->edate->format('d') : '' ) }}</span></div>
						<div class="ev-list-detail">
							<p class="tit"><span class="ev-cate0{{ $list->category }}">[{{ config('site.board')[$code]['Category'][$list->category] }}]</span> {{ $list->subject }}</p>
						</div> 
					</li>
					@endforeach
					
					@if( $lists->isEmpty() )
					<li class="no-data">
						<div class="date">{{ $date['year'] }}-{{ $date['month'] }}</div>
						<div class="ev-list-detail">
							<p class="tit no-cate">등록된 일정이 없습니다.</p>
						</div>
					</li>
					@endif
				</ul>
			</div>
		</div>
	</div>		
</div>		
@endsection		
