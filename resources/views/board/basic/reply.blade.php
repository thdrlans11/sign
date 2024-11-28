@extends('include.layout')

@include('board.'.config('site.board')[$code]['Skin'].'.script')

@section('content')
<div class="formArea bbsWrite pcOnly">
	<form id="bbs_form" action="@if( !isset($data) ){{ route('board.replyInsert', ['code'=>$code, 'parent_sid'=>$parent_data['sid']]) }}@else{{ route('board.replyUpdate', ['code'=>$code, 'board'=>$data['sid']]) }}@endif" method="post" onsubmit="return check_bbs(this);" enctype="multipart/form-data">
		{{ csrf_field() }}
		<fieldset>
			<legend>게시판 글쓰기</legend>
			<table class="inputTbl">
				<colgroup>
					<col style="width:15%;">
					<col style="width:*;">
				</colgroup>
				<tbody>
					<tr>
						<th>작성자</th>
						<td>{{ old('name', $data['name'] ?? Auth::user()->user_name) }}</td>							
					</tr>
					<tr>
						<th><label for="">이메일</label></th>
						<td><input type="text" value="{{ old('name', $data['email'] ?? Auth::user()->email) }}" name="email" id="email" style="width: 100%;"></td>
					</tr>
					<tr>
						<th><label for="">제목</label></th>
						<td class="multi">
							<input type="text" value="{{ old('subject', $data['subject'] ?? '[답글] - '.$parent_data['subject']) }}" name="subject" id="subject" style="width: calc(100% - 160px);">
						</td>
					</tr>
					@if( config('site.board')[$code]['UseLink'] )
					<tr>
						<th><label for="">LINK URL</label></th>
						<td><input type="text" value="{{ old('linkurl', $data['linkurl'] ?? '') }}" name="linkurl" id="linkurl" style="width: 100%;"></td>
					</tr>
					@endif

					@if( isset($files) && $files->isNotEmpty() )
					<tr>
						<th><label for="">첨부파일 관리</label></th>
						<td>
							@foreach( $files as $key => $val )
							<input type="checkbox" class="lm0 tm10" name="del_file[]" value="{{ $val['sid'] }}"> {{ $val['filename'] }} - 삭제  <br>
							@endforeach
						</td>
					</tr>
					@endif

					<tr>
						<td class="pluginArea" colspan="2" style="padding:10px"> 
							<textarea name="content" id="content">
								{{ old('content', $data['content'] ?? '[원본]<br>'.$parent_data['content'].'<br>---------------------------------------------------') }}
							</textarea>
						</td>
					</tr>
					<tr>
						<td class="pluginArea" colspan="2">
							<div id="plupload"></div>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="btnArea btn">
				<input type="submit" value="{{ isset($data) ? '수정' : '등록' }}" class="btnBdDef">
				<input type="reset" value="취소" class="btnGrey">
			</div>
		</fieldset>
	</form>
</div>

<div class="mobileNote">
	<img src="/image/common/pcOnly.png" alt="PC에서만 이용 가능합니다">				
</div>

@endsection