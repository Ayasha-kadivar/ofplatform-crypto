<div class="input-group w-auto flex-fill">
    <select name="amount_type" class="form-control">
        <option value="">Select Type</option>
        <option value="total" <?php if(request()->amount_type === 'total'): ?> selected <?php endif; ?>>Search by Total Balance</option>
        <option value="deposit" <?php if(request()->amount_type === 'deposit'): ?> selected <?php endif; ?>>Search by Deposit Wallet</option>
        <option value="cubeone" <?php if(request()->amount_type === 'cubeone'): ?> selected <?php endif; ?>>Search by Cube One</option>
    </select>
    <input type="number" name="amount" class="form-control bg--white" placeholder="Search Amount" value="<?php echo request()->amount; ?>">
    <select name="sort" class="form-control">
        <option value="">Sort By</option>
        <option value="smallest" <?php if(request()->sort === 'smallest'): ?> selected <?php endif; ?>>Smallest to Largest</option>
        <option value="largest" <?php if(request()->sort === 'largest'): ?> selected <?php endif; ?>>Largest to Smallest</option>
    </select>
    <button class="btn btn--primary input-group-text"><i class="la la-search"></i></button>
</div>