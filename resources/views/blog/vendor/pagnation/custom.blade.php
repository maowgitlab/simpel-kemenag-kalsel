{{-- resources/views/vendor/pagination/custom.blade.php --}}
@if ($paginator->hasPages())
  <div class="text-start py-4">
    <div class="custom-pagination">
      {{-- Sebelumnya Page Link --}}
      @if ($paginator->onFirstPage())
        <span class="prev disabled">Sebelumnya</span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" class="prev">Sebelumnya</a>
      @endif

      {{-- Pagination Elements --}}
      @php
        $start = max($paginator->currentPage() - 2, 1);
        $end = min($paginator->currentPage() + 2, $paginator->lastPage());
      @endphp

      @if ($start > 1)
        <a href="{{ $paginator->url(1) }}">1</a>
        @if ($start > 2)
          <span class="dots">...</span>
        @endif
      @endif

      @for ($i = $start; $i <= $end; $i++)
        @if ($i == $paginator->currentPage())
          <a href="#" class="active">{{ $i }}</a>
        @else
          <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
        @endif
      @endfor

      @if ($end < $paginator->lastPage())
        @if ($end < $paginator->lastPage() - 1)
          <span class="dots">...</span>
        @endif
        <a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
      @endif

      {{-- Selanjutnya Page Link --}}
      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="next">Selanjutnya</a>
      @else
        <span class="next disabled">Selanjutnya</span>
      @endif
    </div>
  </div>
@endif
