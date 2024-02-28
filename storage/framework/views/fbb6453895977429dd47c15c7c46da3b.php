<div class="modal-body">
    <!-- Display Eclaim history -->
    <div>
        <?php if(!empty($eclaim->receipt)): ?>
            <img src="<?php echo e(asset('eclaimreceipts/'.$eclaim->receipt)); ?>" style="height: auto; max-height: 250px; width: auto; max-width: 500px;" alt="Receipt Image">
        <?php else: ?>
            <p>No Receipt available</p>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\hrmgo\resources\views/eclaim/receipt.blade.php ENDPATH**/ ?>