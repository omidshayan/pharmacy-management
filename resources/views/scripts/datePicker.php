<script>
    document.addEventListener("DOMContentLoaded", () => {
        jalaliDatepicker.startWatch({
            persianDigits: true,
            showEmptyBtn: false
        });

        const g2j = (gy, gm, gd) => {
            const g_d_m = [0, 31, (gy % 4 === 0 && gy % 100 !== 0) || gy % 400 === 0 ? 29 : 28,
                31, 30, 31, 30, 31, 31, 30, 31, 30, 31
            ];
            let gy2 = gy - 1600,
                gm2 = gm - 1,
                gd2 = gd - 1;
            let g_day_no = 365 * gy2 + Math.floor((gy2 + 3) / 4) -
                Math.floor((gy2 + 99) / 100) + Math.floor((gy2 + 399) / 400);
            for (let i = 0; i < gm2; ++i) g_day_no += g_d_m[i + 1];
            g_day_no += gd2;
            let j_day_no = g_day_no - 79,
                j_np = Math.floor(j_day_no / 12053);
            j_day_no %= 12053;
            let jy = 979 + 33 * j_np + 4 * Math.floor(j_day_no / 1461);
            j_day_no %= 1461;
            if (j_day_no >= 366) {
                jy += Math.floor((j_day_no - 1) / 365);
                j_day_no = (j_day_no - 1) % 365;
            }
            const j_m = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
            let jm = 0;
            for (; jm < 11 && j_day_no >= j_m[jm]; ++jm) j_day_no -= j_m[jm];
            return [jy, jm + 1, j_day_no + 1];
        };

        const j2g = (jy, jm, jd) => {
            jy -= 979;
            jm--;
            jd--;
            let j_day_no = 365 * jy + Math.floor(jy / 33) * 8 + Math.floor(((jy % 33) + 3) / 4);
            const j_m = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
            for (let i = 0; i < jm; ++i) j_day_no += j_m[i];
            j_day_no += jd;
            let g_day_no = j_day_no + 79;
            let gy = 1600 + 400 * Math.floor(g_day_no / 146097);
            g_day_no %= 146097;
            let leap = true;
            if (g_day_no >= 36525) {
                g_day_no--;
                gy += 100 * Math.floor(g_day_no / 36524);
                g_day_no %= 36524;
                if (g_day_no >= 365) g_day_no++;
                else leap = false;
            }
            gy += 4 * Math.floor(g_day_no / 1461);
            g_day_no %= 1461;
            if (g_day_no >= 366) {
                leap = false;
                g_day_no--;
                gy += Math.floor(g_day_no / 365);
                g_day_no %= 365;
            }
            const g_m = [31, (leap ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            let gm = 0;
            for (; gm < 12 && g_day_no >= g_m[gm]; ++gm) g_day_no -= g_m[gm];
            return [gy, gm + 1, g_day_no + 1];
        };

        const groups = document.querySelectorAll(".one");
        const now = new Date();
        const jNow = g2j(now.getFullYear(), now.getMonth() + 1, now.getDate());
        const today = `${jNow[0]}/${String(jNow[1]).padStart(2,'0')}/${String(jNow[2]).padStart(2,'0')}`;
        const timestampNow = Math.floor(now.getTime() / 1000);

        groups.forEach(group => {
            const view = group.querySelector(".date-view");
            const hidden = group.querySelector(".date-server");

            if (!view || !hidden) return;

            view.value = today;
            hidden.value = timestampNow;

            view.addEventListener("change", () => {
                const [jy, jm, jd] = view.value.split('/').map(Number);
                const [gy, gm, gd] = j2g(jy, jm, jd);
                hidden.value = Math.floor(new Date(gy, gm - 1, gd).getTime() / 1000);
            });
        });
    });
</script>