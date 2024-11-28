<ul class="pager">
    @if( $list->hasPages() )
        @if( !$list->onFirstPage() )
        <li class="first"><a href="{{ $list->url(1) }}"><img alt="처음" src="/devAdmin/image/block_prev.png"></a></li>
        <li class="prev"><a href="{{ $list->previousPageUrl() }}"><img alt="이전" src="/devAdmin/image/prev.png"></a></li>
        @endif

        @foreach( $elements as $element )
            @if( is_string($element) )
                <li class="disabled"><a>{{ $element }}</a></li>
            @endif

            @if( is_array($element) )
                @foreach( $element as $page => $url )
                    @if( $page == $paginator->currentPage() )
                        <li><a href="javascript:void(0);" class="on" onclick="return false;">{{ $page }}</a></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if(  $list->hasMorePages() )
        <li class="next"><a href="{{ $list->nextPageUrl() }}"><img alt="다음" src="/devAdmin/image/next.png"></a></li>
        <li class="last"><a href="{{ $list->url($list->lastPage()) }}"><img alt="마지막" src="/devAdmin/image/block_next.png"></a></li>
        @endif
    @endif
</ul>

