<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">

        <?php if (!empty($error)): ?>
            <div id="flashToastError"
                class="toast align-items-center text-bg-danger border-0"
                role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body small">
                        <?= htmlspecialchars($error) ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div id="flashToastSuccess"
                class="toast align-items-center text-bg-success border-0"
                role="alert" aria-live="polite" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body small">
                        <?= htmlspecialchars($success) ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

    </div>