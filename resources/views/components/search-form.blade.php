@props([
    'placeholder' => 'Search...',
    'placeholderhash' => 'Search...',
    'btn' => 'btn--primary',
    'dateSearch' => 'no',
    'keySearch' => 'yes',
    'amountSearch' => 'No',
    'hashSearch' => 'No'
])

<form action="" method="GET" class="d-flex flex-wrap gap-2">
    @if ($hashSearch == 'yes')
        <x-search-hash placeholder="{{ $placeholderhash }}" />
    @endif
    @if ($amountSearch == 'yes')
        <x-search-amount />
    @endif
    @if ($keySearch == 'yes')
        <x-search-key-field placeholder="{{ $placeholder }}" btn="{{ $btn }}" />
    @endif
    @if ($dateSearch == 'yes')
        <x-search-date-field />
    @endif
</form>