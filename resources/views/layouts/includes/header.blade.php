<!-- Page Header -->
<div class="row">
    <div class="col-lg-12">
        @if(!empty($back))
        <button class="back-btn col-lg-3">Back</button>
        @endif
        <h1 class="page-header">
            @if(!empty($heading))
            {{ $heading }}
            @endif
            @if(!empty($subHeading))
            <small>{{ $subHeading }}</small>
            @endif
        </h1>
    </div>
</div>