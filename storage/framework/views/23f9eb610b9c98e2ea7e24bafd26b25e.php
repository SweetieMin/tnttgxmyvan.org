<div>
    <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
    <div class="header-search">
        <form wire:submit.prevent="search">
            <div class="form-group mb-0 position-relative" id="search-form">
                <i class="dw dw-search2 search-icon"></i>
                <input type="text" class="form-control search-input" placeholder="Tìm kiếm..." id="search"
                    wire:model="keyword" wire:keydown="getSuggestions" autocomplete="off"/>
                <!-- Gợi ý -->

                <?php if(!empty($suggestions) && strlen($keyword) > 0): ?>
                    <ul class="list-group position-absolute w-100" style="top: 100%; z-index: 1000;">
                        <?php $__currentLoopData = $suggestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $suggestion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item list-group-item-action"
                                wire:click="selectSuggestion('<?php echo e($suggestion['route']); ?>')">
                                <?php echo e($suggestion['name']); ?>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>

            </div>
        </form>
    </div>
</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/settings/searches.blade.php ENDPATH**/ ?>