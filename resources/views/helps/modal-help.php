<style>
    .help-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        font-family: sans-serif;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        background: rgba(0, 0, 0, 0.4);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        padding-top: 150px;
    }

    .help-modal.show {
        opacity: 1;
        pointer-events: auto;
    }

    .help-modal.hidden {
        display: none;
    }

    .help-modal-box {
        background: var(--main);
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        overflow-y: auto;
        padding: 20px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        transform: scale(0.8);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .help-modal.show .help-modal-box {
        transform: scale(1);
        opacity: 1;
    }

    .help-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        margin-bottom: 12px;
        font-size: 18px;
    }

    .help-modal-close {
        font-size: 29px;
        cursor: pointer;
        user-select: none;
        background: none;
        border: none;
        line-height: 1;
        padding: 0 6px;
        transition: color 0.2s ease;
        color: var(--text);
    }

    .help-modal-close:hover {
        color: #d33;
    }

    .help-modal-body {
        line-height: 1.8;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        color: white;
        transition: background-color 0.3s ease;
        padding: 8px 20px;
        font-size: 14px;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>

<div id="helpModal" class="help-modal hidden" role="dialog" aria-modal="true" aria-labelledby="helpModalTitle" aria-describedby="helpModalContent">
    <div class="help-modal-box" role="document">
        <div class="help-modal-header">
            <div id="helpModalTitle" class="fs14"></div>
            <button type="button" class="help-modal-close" aria-label="بستن مودال">&times;</button>
        </div>
        <div class="help-modal-body color text-justify fs16" id="helpModalContent"></div>
        <div class="help-modal-footer" style="text-align:center; margin-top:20px;">
            <button type="button" id="helpModalOkBtn" class="btn-primary">متوجه شدم</button>
        </div>
    </div>
</div>

<script>
    const helpsData = <?= json_encode($helps ?? [], JSON_UNESCAPED_UNICODE) ?>;
    document.addEventListener('click', function(e) {
        if (e.target.closest('.help-icon')) {
            const icon = e.target.closest('.help-icon');
            const helpId = icon.dataset.helpId;

            if (helpId && helpsData[helpId]) {
                const {
                    title,
                    content
                } = helpsData[helpId];
                const modal = document.getElementById('helpModal');

                document.getElementById('helpModalTitle').innerText = title;
                document.getElementById('helpModalContent').innerHTML = content;

                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);
            }
        }

        if (
            e.target.classList.contains('help-modal') ||
            e.target.classList.contains('help-modal-close') ||
            e.target.id === 'helpModalOkBtn'
        ) {
            closeModal();
        }
    });

    function closeModal() {
        const modal = document.getElementById('helpModal');
        modal.classList.remove('show');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>