<script>
    const openBtnCont = document.getElementById('openModal-cont');
    const closeBtnCont = document.getElementById('closeModal-cont');
    const overlayCont = document.getElementById('modalOverlay-cont');

    function openModalCont() {
        overlayCont.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModalCont() {
        overlayCont.classList.remove('show');
        document.body.style.overflow = '';
    }

    window.addEventListener('DOMContentLoaded', () => {
        openModalCont();
    });

    openBtnCont.addEventListener('click', openModalCont);

    closeBtnCont.addEventListener('click', closeModalCont);

    overlayCont.addEventListener('click', (e) => {
        if (e.target === overlayCont) {
            closeModalCont();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && overlayCont.classList.contains('show')) {
            closeModalCont();
        }
    });
</script>
<style>
    /* Overlay */
    .modal-overlay-cont {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: block;
        opacity: 0;
        pointer-events: none;
        z-index: 999;
        transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modal-overlay-cont.show {
        opacity: 1;
        pointer-events: auto;
    }
    .modal-cont {
        width: calc(100% - 120px) !important;
        margin: 30px auto 0 auto;
        height: calc(100svh - 67px);
        background: var(--main);
        border-radius: 12px;
        overflow: hidden;
        padding: 0 10px 0 10px;
        position: relative;
        opacity: 0;
        transform: translateY(-30px) scale(0.95);
        filter: blur(2px);
        transition:
            opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1),
            transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
            filter 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
    }
    .modal-overlay-cont.show .modal-cont {
        opacity: 1;
        transform: translateY(0) scale(1);
        filter: blur(0);
    }
    .colse-btn-modal {
        text-align: right;
    }
    /* Close Button */
    .close-btn-cont {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: red;
        font-weight: bold;
        margin: 7px 7px 0 0;
        direction: rtl !important;
    }
    .addBtn {
        width: 140px;
        background-color: var(--bg) !important;
        cursor: pointer;
        color: var(--text) !important;
        margin: 20px 30px 0 0;
        transition: all .3s ease-in;
        border-radius: 3px !important;
        font-size: 15px;
        border: 1px solid var(--bg) !important;
        padding: 10px;
        font-weight: bold;
    }
    .addBtn:hover {
        background-color: var(--main) !important;
        color: var(--text) !important;
        border: 1px solid var(--bg);
    }

    /* start modal data */

    .product-modal-left{
        width: 25%;
        margin-right: 20px;
    }  
    .product-modal-right{
        width: 78%;
    }
    .flex-start{
        align-items: flex-start !important;
    }

</style>