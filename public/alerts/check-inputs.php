<script>
    document.addEventListener('DOMContentLoaded', function() {
        const submitBtn = document.getElementById('submit');

        submitBtn.addEventListener('click', function(e) {
            let isValid = true;
            let firstInvalid = null;

            // inputs check
            document.querySelectorAll('.checkInput').forEach(input => {
                const empty = input.value.trim() === '';
                input.classList.toggle('border-error', empty);

                if (empty) {
                    if (!firstInvalid) firstInvalid = input;
                    isValid = false;
                    input.classList.add('shake');
                    setTimeout(() => input.classList.remove('shake'), 300);
                }
            });

            // group inputs check
            const groupInputs = document.querySelectorAll('.checkInputGroup');
            if (groupInputs.length) {
                const hasValue = [...groupInputs].some(i => i.value.trim() !== '');
                groupInputs.forEach(i => {
                    i.classList.toggle('border-error', !hasValue);
                    if (!hasValue) {
                        if (!firstInvalid) firstInvalid = i;
                        i.classList.add('shake');
                        setTimeout(() => i.classList.remove('shake'), 300);
                    }
                });
                if (!hasValue) isValid = false;
            }

            // select check
            document.querySelectorAll('.checkSelect').forEach(select => {
                const invalid = select.value.trim() === '' || select.selectedOptions[0]?.disabled;
                select.classList.toggle('select-error', invalid);

                [...select.options].forEach(o => o.classList.remove('select-error-option'));

                if (invalid) {
                    select.selectedOptions[0]?.classList.add('select-error-option');
                    if (!firstInvalid) firstInvalid = select;
                    isValid = false;
                    select.classList.add('shake');
                    setTimeout(() => select.classList.remove('shake'), 300);
                }
            });

            // radio check
            const radios = document.querySelectorAll('.checkRadioGroup');
            if (radios.length) {
                const name = radios[0].name;
                const checked = document.querySelector(`input[name="${name}"]:checked`);

                if (!checked) {
                    isValid = false;
                    const box = radios[0].closest('.border');
                    if (box) {
                        if (!firstInvalid) firstInvalid = box;
                        box.classList.add('border-error', 'shake');
                        setTimeout(() => box.classList.remove('shake'), 300);
                    }
                }
            }

            // prevent
            if (!isValid) {
                e.preventDefault();
                firstInvalid?.focus();
            }

        });

        // remove err inputs
        document.querySelectorAll('.checkInput, .checkInputGroup').forEach(input => {
            input.addEventListener('input', () => {
                if (input.classList.contains('checkInputGroup')) {
                    const g = document.querySelectorAll('.checkInputGroup');
                    const ok = [...g].some(i => i.value.trim() !== '');
                    g.forEach(i => i.classList.toggle('border-error', !ok));
                } else {
                    input.classList.toggle('border-error', input.value.trim() === '');
                }
            });
        });

        // remove err select
        document.querySelectorAll('.checkSelect').forEach(select => {
            select.addEventListener('change', () => {
                const invalid = select.value.trim() === '' || select.selectedOptions[0]?.disabled;
                select.classList.toggle('select-error', invalid);
                [...select.options].forEach(o => o.classList.remove('select-error-option'));
                if (invalid) select.selectedOptions[0]?.classList.add('select-error-option');
            });
        });

        // remove err radio
        document.querySelectorAll('.checkRadioGroup').forEach(r => {
            r.addEventListener('change', () => {
                r.closest('.border')?.classList.remove('border-error', 'shake');
            });
        });

    });
</script>