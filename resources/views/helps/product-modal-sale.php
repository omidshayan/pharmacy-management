<style>
    .product-infos-modal {
        position: fixed;
        inset: 0;
        z-index: 99999999;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        background: rgba(0, 0, 0, 0.4);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        padding-top: 150px;
        font-family: sans-serif;
        margin-right: 0 !important;
    }

    .product-infos-modal.show {
        opacity: 1;
        pointer-events: auto;
    }

    .product-infos-modal.hidden {
        display: none;
    }

    .product-infos-modal-box {
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

    .product-infos-modal.show .product-infos-modal-box {
        transform: scale(1);
        opacity: 1;
    }

    .product-infos-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        margin-bottom: 12px;
        font-size: 18px;
    }
    .product-infos-modal-body {
        line-height: 1.6;
    }
</style>
<div id="productInfosModal" class="product-infos-modal hidden" role="dialog" aria-modal="true" aria-labelledby="productInfosModalTitle" aria-describedby="productInfosModalContent">
    <div class="product-infos-modal-box">
        <div class="product-infos-modal-header">
            <div id="productInfosModalTitle" class="fs14"></div>
        </div>
        <div class="product-infos-modal-body" id="productInfosModalContent">
            <!-- datas -->
        </div>
        <div class="product-infos-modal-footer flex-justify-align gap20">
            <button type="button" id="productInfosModalCancelBtn" class="btn-primary fs14">بستن</button>
            <!-- <button type="button" id="productInfosModalOkBtn" class="btn-primary bold">ثبت</button> -->
        </div>
    </div>
</div>
