@if( $list->hasPages() )
<div class="paging-wrap">
    <ul class="paging">
        @if( !$list->onFirstPage() )
        <li class="first"><a href="{{ $list->url(1) }}"><span class="hide">처음</span></a></li>
        <li class="prev"><a href="{{ $list->previousPageUrl() }}"><span class="hide">이전</span></a></li>
        @endif

        @foreach( $elements as $element )
            @if( is_string($element) )
                <li class="disabled"><a>{{ $element }}</a></li>
            @endif

            @if( is_array($element) )
                @foreach( $element as $page => $url )
                    @if( $page == $paginator->currentPage() )
                        <li class="num on"><a href="javascript:void(0);" onclick="return false;">{{ $page }}</a></li>
                    @else
                        <li class="num"><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if(  $list->hasMorePages() )
        <li class="next"><a href="{{ $list->nextPageUrl() }}"><span class="hide">다음</span></a></li>
        <li class="last"><a href="{{ $list->url($list->lastPage()) }}"><span class="hide">마지막</span></a></li>
        @endif
    </ul>
</div> 
@endif

