<style>
    .cart-added-overlay[hidden] {
        display: none;
    }

    .cart-added-overlay {
        position: fixed;
        inset: 0;
        z-index: 1200;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(238, 241, 247, .88);
    }

    .cart-added-dialog {
        width: 100%;
        max-width: 420px;
        overflow: hidden;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 15px 45px rgba(27, 38, 59, .2);
    }

    .cart-added-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 18px;
        background: #17377f;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
    }

    .cart-added-close {
        padding: 0 4px;
        border: 0;
        background: transparent;
        color: #fff;
        font-size: 24px;
        line-height: 1;
        cursor: pointer;
    }

    .cart-added-content {
        padding: 18px 16px 20px;
        color: #333;
    }

    .cart-added-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-bottom: 14px;
        border-bottom: 1px solid #e8e8e8;
    }

    .cart-added-item img {
        width: 44px;
        height: 54px;
        object-fit: contain;
    }

    .cart-added-item-details {
        flex: 1;
        min-width: 0;
    }

    .cart-added-item-name {
        display: block;
        margin-bottom: 5px;
        color: #333;
        font-size: 14px;
        font-weight: 500;
    }

    .cart-added-item-meta,
    .cart-added-item-total {
        font-size: 13px;
    }

    .cart-added-item-meta {
        color: #279b4b;
        font-weight: 600;
    }

    .cart-added-item-total {
        white-space: nowrap;
        font-weight: 600;
    }

    .cart-added-summary {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e8e8e8;
        font-size: 14px;
    }

    .cart-added-shipping {
        padding-top: 5px;
        color: #888;
        font-size: 12px;
    }

    .cart-added-total {
        padding: 12px 0 4px;
        border: 0;
        font-size: 16px;
    }

    .cart-added-actions {
        display: grid;
        gap: 8px;
        padding: 0 16px 18px;
    }

    .cart-added-checkout {
        display: block;
        padding: 12px;
        border-radius: 8px;
        background: #25a447;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
    }

    .cart-added-checkout:hover,
    .cart-added-checkout:focus {
        background: #208b3d;
        color: #fff;
        text-decoration: none;
    }

    .cart-added-view {
        color: #17377f;
        font-size: 13px;
        text-align: center;
    }
</style>

<div class="cart-added-overlay" id="cartAddedOverlay" hidden aria-hidden="true">
    <section class="cart-added-dialog" role="dialog" aria-modal="true" aria-labelledby="cartAddedTitle">
        <div class="cart-added-heading">
            <span id="cartAddedTitle">Your Basket</span>
            <button type="button" class="cart-added-close" id="cartAddedClose" aria-label="Close basket">&times;</button>
        </div>
        <div class="cart-added-content">
            <div class="cart-added-item">
                <img id="cartAddedImage" src="" alt="">
                <div class="cart-added-item-details">
                    <span class="cart-added-item-name" id="cartAddedName"></span>
                    <span class="cart-added-item-meta" id="cartAddedItemMeta"></span>
                </div>
                <span class="cart-added-item-total" id="cartAddedItemTotal"></span>
            </div>
            <div class="cart-added-summary">
                <span>Subtotal</span>
                <strong id="cartAddedSubtotal"></strong>
            </div>
            <div class="cart-added-shipping">Shipping fee is calculated at checkout.</div>
            <div class="cart-added-summary cart-added-total">
                <strong>Total</strong>
                <strong id="cartAddedTotal"></strong>
            </div>
        </div>
        <div class="cart-added-actions">
            <a class="cart-added-checkout" href="{{ route('checkout.index') }}">Proceed to Checkout</a>
            <a class="cart-added-view" href="{{ route('carts.index') }}">View Cart</a>
        </div>
    </section>
</div>
