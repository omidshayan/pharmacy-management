<style>
    .type-transaction {
        width: 50%;
        margin: 15px 0;
        margin: 0 auto !important;
    }

    .type-transaction input {
        display: none;
    }

    .type-switch {
        position: relative;
        display: flex;
        background: var(--similar_text);
        border-radius: 14px;
        padding: 5px;
        height: 48px;
        direction: rtl;
        background-color: var(--main);
    }

    .type-switch label {
        flex: 1;
        text-align: center;
        line-height: 38px;
        font-weight: 600;
        cursor: pointer;
        z-index: 2;
        transition: color 0.3s ease;
        user-select: none;
    }

    .type-slider {
        position: absolute;
        top: 5px;
        right: 5px;
        width: calc(50% - 5px);
        height: 38px;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border-radius: 12px;
        opacity: 0;
        transform: scale(0.9);
        transition:
            transform 0.35s cubic-bezier(0.4, 0, 0.2, 1),
            right 0.35s cubic-bezier(0.4, 0, 0.2, 1),
            opacity 0.2s ease;
    }

    #type-receive:checked~.type-slider {
        right: 5px;
        opacity: 1;
        transform: scale(1);
    }

    #type-pay:checked~.type-slider {
        right: calc(50% + 2.5px);
        opacity: 1;
        transform: scale(1);
    }

    #type-receive:checked~label[for="type-receive"],
    #type-pay:checked~label[for="type-pay"] {
        color: #fff;
    }

    .type-transaction {
        border: 1px solid var(--border) !important;
        border-radius: 14px;
    }
    .type-error {
        border-radius: 14px;
        border: 1px solid red !important;
    }

</style>

<!-- check select type -->
<script>
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        const radios = document.querySelectorAll('input[name="type"]');
        const box = document.getElementById('transactionTypeBox');

        let checked = false;

        radios.forEach(radio => {
            if (radio.checked) checked = true;
        });

        if (!checked) {
            e.preventDefault();
            box.classList.add('type-error', 'shake');
        }
    });

    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('transactionTypeBox')
                .classList.remove('type-error');
        });
    });
</script>