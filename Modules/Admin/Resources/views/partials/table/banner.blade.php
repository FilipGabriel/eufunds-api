<div class="thumbnail-banner-holder">
    @if ($file->exists)
        <img src="{{ $file->path }}" alt="thumbnail">
    @else
        <i class="fa fa-picture-o"></i>
    @endif
</div>
