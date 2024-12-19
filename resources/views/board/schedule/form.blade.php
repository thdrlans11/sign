@extends('include.layout')

@include('board.'.config('site.board')[$code]['Skin'].'.script')

@section('content')
<div class="notice-wrap inner-layer">
		<!-- 공지 type1 -->
	<div class="ev-wrap type1">
		<div class="write-form-wrap">
			<form id="bbs_form" action="{{ route('board.upsert', ['code'=>$code, 'sid'=>!empty($data)?base64_encode($data['sid']):'']) }}" method="post" onsubmit="return check_bbs(this);" enctype="multipart/form-data">
				{{ csrf_field() }}
				<fieldset>
					<legend class="hide">교육일정 등록</legend>
					<ul class="write-wrap mt0">
						@if( config('site.board')[$code]['UseCategory'] )
						<li>
							<div class="form-tit"><strong class="required">*</strong> 행사구분</div>
							<div class="form-con">
								<div class="radio-wrap">
									@foreach( config('site.board')[$code]['Category'] as $key => $val )
									<label for="category{{ $key }}" class="radio-group">
										<input type="radio" name="category" id="category{{ $key }}" value="{{ $key }}" {{ ( $data->category ?? '' ) == $key ? 'checked' : '' }}>{{ $val }}
									</label>
									@endforeach
								</div>
							</div>
						</li>							
						@endif
						<li>
							<div class="form-tit"><strong class="required">*</strong> 교육명</div>
							<div class="form-con">
								<input type="text" name="subject" id="subject" value="{{ $data->subject ?? '' }}" class="form-item"/>
								<div class="checkbox-wrap mt-10">
									@if( config('site.board')[$code]['UseNotice'] )
									<label for="notice" class="checkbox-group">
										<input type="checkbox" name="notice" id="notice" value="Y" {{ ( $data->notice ?? '' ) == 'Y' ? 'checked' : '' }}>공지
									</label>
									@endif
									@if( config('site.board')[$code]['UseMain'] )
									<label for="main" class="checkbox-group">
										<input type="checkbox" name="main" id="main" value="Y" {{ ( $data->main ?? '' ) == 'Y' ? 'checked' : '' }}>push
									</label>
									@endif
								</div>
							</div>
						</li>
						<li>
							<div class="form-tit"><strong class="required">*</strong> 교육기간</div>
							<div class="form-con">
								<div class="radio-wrap">
									@foreach( config('site.board')[$code]['DateType'] as $key => $val )
									<label for="date_type{{ $key }}" class="radio-group">
										<input type="radio" name="date_type" id="date_type{{ $key }}" value="{{ $key }}" {{ ( $data->date_type ?? '' ) == $key ? 'checked' : '' }}>{{ $val }}
									</label>	
									@endforeach
								</div>
							</div>							
						</li>
						<li class="date_box" @if( !isset($data->date_type) ) style="display:none" @endif >
							<div class="form-tit"><strong class="required">*</strong> 교육일</div>
							<div class="form-con">
								<div class="form-group-text">
									<input type="text" value="{{ $data->sdate ?? '' }}" name="sdate" id="sdate" style="width: 20%;" readonly class="dateTimeCalendar form-item small">
									<span>~</span>
									<input type="text" value="{{ $data->edate ?? '' }}" name="edate" id="edate" style="width: 20%;" readonly class="dateTimeCalendar form-item small" {{ ( $data->date_type ?? '' ) != 'L' ? 'disabled' : '' }}> 
								</div>							
							</div>	
						</li>
						<li>
							<div class="form-tit"><strong class="required">*</strong> 장소</div>
							<div class="form-con">
								<input type="text" name="place" id="place" value="{{ $data->place ?? '' }}" class="form-item">
							</div>
						</li>
						<li>
							<div class="form-tit">주최</div>
							<div class="form-con">
								<input type="text" name="sponsor" id="sponsor" value="{{ $data->sponsor ?? '' }}" class="form-item">
							</div>
						</li>
						<li>
							<div class="form-tit">문의처</div>
							<div class="form-con">
								<input type="text" name="inquiry" id="inquiry" value="{{ $data->inquiry ?? '' }}" class="form-item">
							</div>
						</li>
						@if( config('site.board')[$code]['UseLink'] )
						<li>
							<div class="form-tit">홈페이지</div>
							<div class="form-con">
								<input type="text" name="linkurl" id="linkurl" value="{{ $data->linkurl ?? '' }}" class="form-item"/>
							</div>	
						</li>
						@endif
						@if( isset($files) && $files->isNotEmpty() )
						<li>
							<div class="form-tit">첨부파일 관리</div>
							<div class="checkbox-wrap mt-10">
								@foreach( $files as $key => $val )
								<label for="" class="checkbox-group">
									<input type="checkbox" class="lm0 tm10" name="del_file[]" value="{{ $val['sid'] }}"> {{ $val['filename'] }} - 삭제  <br>
								</label>
								@endforeach
							</div>
						</li>
						@endif

						@if( config('site.board')[$code]['UsePopup'] )
						<li>
							<div class="form-tit"><strong class="required">*</strong> 팝업 설정</div>
							<div class="form-con">
								<div class="radio-wrap">
									@foreach( config('site.board')['select']['setting'] as $key => $val )
									<label for="popup{{ $key }}" class="radio-group">
										<input type="radio" name="popup" id="popup{{ $key }}" value="{{ $key }}" {{ ( $data->popup ?? 'N' ) == $key ? 'checked' : '' }}>{{ $val }}
									</label>
									@endforeach
								</div>
							</div>
						</li>							
						<li class="popupBox" {{ ( $data->popup ?? '' ) == 'Y' ? '' : 'style=display:none' }}>
							<div class="form-tit">팝업 템플릿</div>
							<div class="form-con">
								<div class="radio-wrap">	
									@foreach( config('site.board')['select']['skin'] as $key => $val )
									<label for="popup_skin{{ $key }}" class="radio-group">
										<input type="radio" name="popup_skin" id="popup_skin{{ $key }}" value="{{ $key }}" {{ ( $popup['skin'] ?? '' ) == $key ? 'checked' : '' }}>{{ $val }}
									</label>
									@endforeach
									<a href="#n" class="btn btn-small" onclick="preview(); return false;">미리보기</a>
								</div>
							</div>
						</li>
						<li class="popupBox" {{ ( $data->popup ?? '' ) == 'Y' ? '' : 'style=display:none' }}>
							<div class="form-tit">팝업 내용 선택</div>
							<div class="form-con">
								<div class="radio-wrap">
									@foreach( config('site.board')['select']['content'] as $key => $val )
									<label for="popup_select{{ $key }}" class="radio-group">
										<input type="radio" name="popup_select" id="popup_select{{ $key }}" value="{{ $key }}" {{ ( $popup['popup_select'] ?? '1' ) == $key ? 'checked' : '' }}>{{ $val }}
									</label>
									@endforeach
								</div>
							</div>
						</li>
						<li class="popupBox" {{ ( $data->popup ?? '' ) == 'Y' ? '' : 'style=display:none' }}>
							<div class="form-tit">팝업 사이즈</div>
							<div class="form-con">
								<div class="form-group">
									<span class="text">사이즈</span> : 
									<input type="text" name="width" id="width" placeholder="500" value="{{ $popup['width'] ?? '500' }}" class="form-item w-10p"> X 
									<input type="text" name="height" id="height" placeholder="400" value="{{ $popup['height'] ?? '400' }}" class="form-item w-10p">
								</div>
								<div class="form-group mt-10">
									<span class="text">위치</span> : 위에서 
									<input type="text" name="position_x" id="position_x"  placeholder="500" value="{{ $popup['position_x'] ?? '500' }}" class="form-item w-10p"> px, 왼쪽에서 
									<input type="text" name="position_y" id="position_y" placeholder="400" value="{{ $popup['position_y'] ?? '400' }}" class="form-item w-10p"> px
								</div>
							</div>
						</li>
						<li class="popupBox" {{ ( $data->popup ?? '' ) == 'Y' ? '' : 'style=display:none' }}>
							<div class="form-tit">팝업 자세히 보기</div>
							<div class="form-con">
								<div class="radio-wrap">
									@foreach( config('site.board')['select']['setting'] as $key => $val )
									<label for="popup_detail{{ $key }}" class="radio-group">
										<input type="radio" name="popup_detail" id="popup_detail{{ $key }}" value="{{ $key }}" {{ ( $popup['popup_detail'] ?? '' ) == $key ? 'checked' : '' }}>{{ $val }}
									</label>
									@endforeach
								</div>
							</div>
						</li>
						<li class="popupDetailBox" {{ ( $popup['popup_detail'] ?? '' ) == 'Y' ? '' : 'style=display:none' }}>
							<div class="form-tit">자세히 보기 LINK</div>
							<div class="form-con">
								<input type="text" name="popup_linkurl" id="popup_linkurl" value="{{ $popup['popup_linkurl'] ?? '' }}" class="form-item"/>
							</div>	
						</li>

						<li class="popupBox" {{ ( $data->popup ?? '' ) == 'Y' ? '' : 'style=display:none' }}>
							<div class="form-tit">팝업 시작일 / 종료일</div>
							<div class="form-con">
								<div class="form-group n2">
									<div class="form-group-text">
										<span class="text">시작일 : </span> <input type="text" name="popup_sdate" id="popup_sdate" value="{{ $popup['startdate'] ?? '' }}" class="form-item dateCalendar" readonly>
									</div>
									<div class="form-group-text">
										<span class="text">종료일 : </span> <input type="text" name="popup_edate" id="popup_edate" value="{{ $popup['enddate'] ?? '' }}" class="form-item dateCalendar" readonly>
									</div>
								</div>
							</div>
						</li>
						<li class="popupContentBox" {{ ( $popup['popup_select'] ?? '' ) == '2' ? '' : 'style=display:none' }}>
							<div class="form-con">
								<textarea name="popup_content" id="popup_content">{{ $popup['popup_content'] ?? '' }}</textarea>
							</div>
						</li>
						@endif
						@if( config('site.board')[$code]['UseHide'] )
						<li>
							<div class="form-tit">공개여부</div>
							<div class="form-con">
								<div class="radio-wrap">
									@foreach( config('site.board')['select']['hide'] as $key => $val )
									<label for="hide{{ $key }}" class="radio-group">
										<input type="radio" name="hide" id="hide{{ $key }}" value="{{ $key }}" {{ ( $data['hide'] ?? '' ) == $key ? 'checked' : '' }}>{{ $val }}
									</label>
									@endforeach
								</div>
							</div>
						</li>
						@endif
						<li>
							<div class="form-con">
								<textarea name="content" id="content">{{ $data['content'] ?? '' }}</textarea>
							</div>
						</li>
						<li>
							<div class="form-con">
								<div id="plupload"></div>
							</div>
						</li>
					</ul>	

					<div class="btn-wrap jc-c">
						<a href="" class="btn calendar line-type cancel" onclick="history.back(-1)">취소</a>
						<button type="submit" class="btn calendar bg-type {{ isset($data->sid) ? 'color4' : 'color5' }}">{{ isset($data->sid) ? '수정' : '등록' }}</button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>
@endsection