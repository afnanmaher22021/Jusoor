@if ($paginator->hasPages())
    <nav class="pagination-wrapper" role="navigation" aria-label="ترقيم الصفحات">
        <ul class="pagination-custom">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="السابق">
                    <span class="page-link prev-next">
                        <span class="page-arrow">→</span>
                        <span>السابق</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link prev-next" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="السابق">
                        <span class="page-arrow">→</span>
                        <span>السابق</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link dots">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link prev-next" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="التالي">
                        <span>التالي</span>
                        <span class="page-arrow">←</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="التالي">
                    <span class="page-link prev-next">
                        <span>التالي</span>
                        <span class="page-arrow">←</span>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
