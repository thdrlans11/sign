@push('scripts')
<script>
$(document).ready(function(){

    $(".comment_modify").click(function(){

        $(this).closest("dl").fadeOut(function(){

            $(this).closest("dl").next().fadeIn();        

        });

        return false;

    });

    $(".comment_reply").click(function(){

        $(this).closest("dl").next().next().fadeIn();        

        return false;

    });

});   
function check_comment(f){
    
    if( $(f.comment).val() == "" ){
        alert("내용을 입력해주세요.");
        return false;
    }

}
</script>
@endpush
<div class="replyArea">
	<div class="brief">댓글 {{ number_format(count($comments)) }}</div>

	@if( in_array( Auth::user()->user_level , config('site.board')[$code]['PermitComment'] ) )
	<div class="replyWrite">
		<h3 class="hidden">댓글 쓰기</h3>
		<form method="post" action="{{ route('board.CommentInsert', ['code'=>$code, 'board'=>$data['sid']]) }}" onsubmit="return check_comment(this)">
			{{ csrf_field(); }}
			<fieldset>
				<legend>댓글쓰기</legend>
				<textarea rows="10" cols="30" name="comment" placeholder="댓글을 입력해주세요."></textarea>
				<span class="btn"><input type="submit" value="등록" class="btnDef"></span>
			</fieldset>
		</form>
	</div>
	@endif

    @foreach( $comments as $key => $c )
	<dl class="replyItem {{ $c['csid']!=$c['sid']?'reply':'' }}">
		<dt>
            {{ $c['name'] }} <span>( {{ $c['signdate'] }} )</span> 
            @if( $c['csid'] == $c['sid'] )
            <a href="#" class="reply comment_reply">댓글</a>
            @endif
        </dt>
		<dd>
			{{ $c['comment'] }}
		</dd>
        @if( Auth::User()->isAdmin() || $c['id'] == Auth::User()->id )
		<dd class="util">							
			<a href="#" class="comment_modify">수정</a>
			<a href="{{ route('board.CommentDelete', ['code'=>$code, 'board'=>$data['sid'], 'comment'=>$c['sid']]) }}" onclick="return confirm('정말 삭제하시겠습니까?')">삭제</a>
		</dd>
        @endif
	</dl>
    
    <dl class="replyItem modify" style="display:none">
		<dd>
			<form method="post" action="{{ route('board.CommentUpdate', ['code'=>$code, 'board'=>$data['sid'], 'comment'=>$c['sid']]) }}" onsubmit="return check_comment(this)">
                {{ csrf_field(); }}
				<fieldset>
					<legend>댓글수정</legend>
					<textarea rows="10" cols="30" name="comment">{{ $c['comment'] }}</textarea>
					<span class="btn"><input type="submit" value="수정" class="btnDef"></span>
				</fieldset>
			</form>
		</dd>
	</dl>

    <div class="replyWrite reply" style="display:none">
		<form method="post" action="{{ route('board.CommentInsert', ['code'=>$code, 'board'=>$data['sid'], 'csid'=>$c['sid']]) }}" onsubmit="return check_comment(this)">
            {{ csrf_field(); }}
			<fieldset>
				<legend>답글쓰기</legend>
				<textarea rows="10" cols="30" name="comment" placeholder="답글을 입력해주세요."></textarea>
				<span class="btn"><input type="submit" value="답글 등록" class="btnDef"></span>
			</fieldset>
		</form>
	</div>
    @endforeach
</div>
