<div class="modal-body">
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Time</th>
                    <th>Message</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($history)): ?>
                    <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($row['username']); ?></td>
                            <td><?php echo e(!empty($row['time']) ? \Auth::user()->dateFormat($row['time']):''); ?></td>
                            <td><?php echo e($row['message'] ?? ''); ?></td>
                            <td><?php echo e($row['comment'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4"><strong>No Record Found!</strong></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH C:\laragon\www\hrmgo\resources\views/eclaim/history.blade.php ENDPATH**/ ?>