<article class="sticky-note {{ $note->color }}" id="note-{{ $note->id }}">
    <form class="ajax-form sticky-note-form" method="POST" action="{{ route('notes.update', $note) }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="category" value="{{ $note->category }}">
        <input type="hidden" name="color" value="{{ $note->color }}">
        <div class="sticky-note-head">
            <input class="sticky-title" name="title" value="{{ $note->title }}" aria-label="Note title">
            <button class="note-icon-btn" type="button" data-action="{{ route('notes.pin', $note) }}" data-method="PATCH" title="Pin note">
                <i data-lucide="star" class="{{ $note->is_pinned ? 'filled-star' : '' }}"></i>
            </button>
        </div>
        <textarea class="sticky-body" name="body" rows="5" aria-label="Note body">{{ $note->body }}</textarea>
        <div class="sticky-note-foot">
            <span>{{ $note->category }} · {{ $note->updated_at->format('d M, h:i A') }}</span>
            <div class="d-flex gap-1">
                <a class="note-icon-btn" href="{{ route('notes.show', $note) }}" title="Open note"><i data-lucide="expand"></i></a>
                <button class="note-icon-btn" type="submit" title="Save note"><i data-lucide="save"></i></button>
                <button class="note-icon-btn danger" type="button" data-action="{{ route('notes.destroy', $note) }}" data-method="DELETE" data-confirm="Delete this note?" title="Delete note"><i data-lucide="trash-2"></i></button>
            </div>
        </div>
    </form>
</article>
