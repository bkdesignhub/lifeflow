<div class="modal fade" id="noteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-5 border-0">
            <div class="modal-header border-0"><h5 class="modal-title">Add Note</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <form class="ajax-form" method="POST" action="{{ route('notes.store') }}" data-reload="true">
                @csrf
                <div class="modal-body vstack gap-3">
                    <input class="form-control form-control-lg" name="title" placeholder="Note title" required>
                    <textarea class="form-control" name="body" rows="5" placeholder="Write your note..."></textarea>
                    <select class="form-select form-select-lg" name="category">@foreach(['Ideas','Learning','Work','Personal','Important'] as $cat)<option>{{ $cat }}</option>@endforeach</select>
                    <input type="hidden" name="color" value="soft-yellow">
                </div>
                <div class="modal-footer border-0"><button class="btn btn-primary rounded-4 w-100">Save Note</button></div>
            </form>
        </div>
    </div>
</div>
