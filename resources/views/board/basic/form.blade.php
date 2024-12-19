@extends('include.layout')

@include('board.'.config('site.board')[$code]['Skin'].'.script')

@section('content')

<div class="notice-wrap inner-layer">

	<!-- s:board -->
	<link href="/assets/css/board.css" rel="stylesheet">

	<div id="board" class="board-wrap">
		<!-- 공지 type1 -->
		<div class="board-write">
			<div class="write-form-wrap">
				<form id="bbs_form" action="{{ route('board.upsert', ['code'=>$code, 'sid'=>!empty($data)?base64_encode($data['sid']):'']) }}" method="post" onsubmit="return check_bbs(this);" enctype="multipart/form-data">
					{{ csrf_field() }}
					<fieldset>
						<legend>게시판 글쓰기</legend>
						<div class="write-contop text-right">
							<div class="help-text"><strong class="required">*</strong> 표시는 필수입력 항목입니다.</div>
						</div>
						<ul class="write-wrap">
							<li>
								<div class="form-tit"><strong class="required">*</strong> 작성자</div>
								<div class="form-con">
									<input type="text" name="name" id="name" value="{{ $data->name ?? auth('web')->user()->name_kr }}" class="form-item small"/>
								</div>	
							</li>	
							<li>
								<div class="form-tit"><strong class="required">*</strong> 이메일</div>
								<div class="form-con">
									<input text="text" name="email" id="email" value="{{ $data->email ?? auth('web')->user()->email }}" class="form-item"/>
								</div>
							</li>
							@if( config('site.board')[$code]['UseCategory'] )
							<li>
								<div class="form-tit"><strong class="required">*</strong> 카테고리</div>
								<div class="form-con">
									<div class="radio-wrap">
										@foreach( config('site.board')[$code]['Category'] as $key => $val )
										<label for="category{{ $key }}">
											<input type="radio" name="category" id="category{{ $key }}" value="{{ $key }}" {{ ( $data->category ?? '' ) == $key ? 'checked' : '' }}>{{ $val }}
										</label>
										@endforeach
									</div>
								</div>
							</li>							
							@endif
							<li>
								<div class="form-tit"><strong class="required">*</strong> 제목</div>
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
							@if( config('site.board')[$code]['UseLink'] )
							<li>
								<div class="form-tit">LINK URL</div>
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
							<a href="" class="btn calendar line-type cancel" onclick="if( confirm('취소하실 경우 작성하신 내역이 저장되지 않습니다.\n취소히시겠습니까?') ){ history.back(-1); }">취소</a>
							<button type="submit" class="btn calendar bg-type {{ isset($data->sid) ? 'color4' : 'color5' }}">{{ isset($data->sid) ? '수정' : '등록' }}</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>			
</div>
@endsection