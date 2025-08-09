@if(isset($href))
    <a href="{{ $href }}">
@endif
    <button class="btn btn-outline-{{ $iconColor }} btn-pill btn-sm"
        @if(!empty($modalTarget)) data-bs-toggle="offcanvas" data-bs-target="#{{ $modalTarget }}" aria-controls="{{ $modalTarget }}" @endif>
        <i class="{{ $iconClass }}"></i>
    </button>
@if(isset($href))
    </a>
@endif