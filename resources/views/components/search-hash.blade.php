@props(['placeholder' => 'Search Hash Id', 'btn' => 'btn--primary'])
<div class="input-group w-auto flex-fill">
    <input type="text" name="deposit_hash" class="form-control bg--white" placeholder="{{ __($placeholder) }}" value="<?php echo request()->deposit_hash; ?>">
    <button class="btn btn--primary input-group-text"><i class="la la-search"></i></button>
</div>