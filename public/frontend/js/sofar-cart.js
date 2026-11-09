/**
 * Sofar: "Add to Cart" buttons on product cards (.js-quick-add[data-url]).
 * Adds 1 item via the carts.add JSON route, updates the header badge/total
 * and shows the "added to cart" pop-up from includes/cart-added-modal.
 */
(function ($) {
    'use strict';

    function formatCurrency(amount) {
        return '₦' + Number(amount).toLocaleString('en-NG', { maximumFractionDigits: 2 });
    }

    function showAddedPopup(response) {
        var $overlay = $('#cartAddedOverlay');

        if (!$overlay.length) {
            return;
        }

        var imageBase = $overlay.data('imageBase') || '/images/products';

        $('#cartAddedName').text(response.item.name);
        $('#cartAddedItemMeta').text(formatCurrency(response.item.price) + ' · Qty: ' + response.item.quantity);
        $('#cartAddedItemTotal').text(formatCurrency(response.item.total));
        $('#cartAddedSubtotal, #cartAddedTotal').text(formatCurrency(response.subtotal));
        $('#cartAddedImage')
            .attr('src', imageBase + '/' + encodeURIComponent(response.item.image))
            .attr('alt', response.item.name);

        $overlay.prop('hidden', false).attr('aria-hidden', 'false');
        $('#cartAddedClose').trigger('focus');
    }

    $(document).on('click', '.js-quick-add', function (event) {
        event.preventDefault();

        var $button = $(this);

        if ($button.hasClass('is-loading')) {
            return;
        }

        $button.addClass('is-loading').prop('disabled', true);

        $.ajax({
            url: $button.data('url'),
            type: 'GET',
            dataType: 'json',
            data: { qty: 1 }
        }).done(function (response) {
            $('.cartReload').text(response.cartQuantity);
            $('.cartReloads').text(formatCurrency(response.subtotal));
            $button.addClass('is-added');
            window.setTimeout(function () { $button.removeClass('is-added'); }, 1800);
            showAddedPopup(response);
        }).fail(function () {
            window.alert('We could not add this product to your cart. Please try again.');
        }).always(function () {
            $button.removeClass('is-loading').prop('disabled', false);
        });
    });

    // Closing the pop-up (also works on pages without the product-page script).
    $(document).on('click', '#cartAddedClose', function () {
        $('#cartAddedOverlay').prop('hidden', true).attr('aria-hidden', 'true');
    });

    $(document).on('click', '#cartAddedOverlay', function (event) {
        if (event.target === this) {
            $(this).prop('hidden', true).attr('aria-hidden', 'true');
        }
    });

    $(document).on('keydown', function (event) {
        var $overlay = $('#cartAddedOverlay');

        if (event.key === 'Escape' && $overlay.length && !$overlay.prop('hidden')) {
            $overlay.prop('hidden', true).attr('aria-hidden', 'true');
        }
    });
})(jQuery);
