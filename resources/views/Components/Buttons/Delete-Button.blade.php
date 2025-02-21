<form action="{{ $route }}" method="POST" style="display: inline;">
    @csrf
    @method('DELETE')
    <button type="submit"
            class="btn btn-outline-danger btn-pill btn-sm"
            onclick="return confirm('{{ $confirmMessage }}');">
        <i class="fa fa-trash"></i>
    </button>
</form>
