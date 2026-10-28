/* Lebas Elementor Suite — frontend interactions */
(function ($) {
    'use strict';

    // ---- Global input normalizer: Persian/Arabic digits -> ASCII ----
    function normalizeDigits(str) {
        if (typeof str !== 'string') return str;
        var fa = '۰۱۲۳۴۵۶۷۸۹';
        var ar = '٠١٢٣٤٥٦٧٨٩';
        var out = '';
        for (var i = 0; i < str.length; i++) {
            var c = str.charAt(i);
            var idx = fa.indexOf(c);
            if (idx > -1) { out += idx; continue; }
            idx = ar.indexOf(c);
            if (idx > -1) { out += idx; continue; }
            out += c;
        }
        return out;
    }
    $(document).on('input', 'input, textarea', function () {
        var $el = $(this);
        var val = $el.val();
        if (typeof val === 'string') {
            var norm = normalizeDigits(val);
            if (norm !== val) $el.val(norm);
        }
    });

    // ---- Product grid column switcher (4 <-> 2) ----
    $(document).on('click', '.lebas-grid-switcher__btn', function () {
        var target = $(this).data('target');
        var $grid = $(this).closest('.lebas-grid-switcher').next('.lebas-product-grid');
        if (!$grid.length) $grid = $('.lebas-product-grid').first();
        $grid.removeClass('cols-4 cols-2').addClass(target === '2' ? 'cols-2' : 'cols-4');
        $(this).siblings().removeClass('is-active');
        $(this).addClass('is-active');
    });

    // ---- Bottom sheet open/close + drag ----
    function openBottomSheet(id) {
        var $bs = $('#' + id);
        if (!$bs.length) return;
        $bs.addClass('is-open');
        $('body').css('overflow', 'hidden');
    }
    function closeBottomSheet($bs) {
        $bs.removeClass('is-open');
        $('body').css('overflow', '');
    }
    $(document).on('click', '[data-lebas-bottomsheet]', function (e) {
        e.preventDefault();
        openBottomSheet($(this).data('lebas-bottomsheet'));
    });
    $(document).on('click', '.lebas-bottomsheet__backdrop, .lebas-bottomsheet__close', function () {
        closeBottomSheet($(this).closest('.lebas-bottomsheet'));
    });
    // drag handle
    $(document).on('mousedown touchstart', '.lebas-bottomsheet__handle', function (e) {
        var $bs = $(this).closest('.lebas-bottomsheet');
        var startY = (e.type.indexOf('touch') === 0) ? e.originalEvent.touches[0].clientY : e.clientY;
        var startH = $bs.height();
        function move(ev) {
            var y = (ev.type.indexOf('touch') === 0) ? ev.originalEvent.touches[0].clientY : ev.clientY;
            var delta = y - startY;
            var newH = Math.max(200, Math.min(window.innerHeight * 0.95, startH - delta));
            $bs.height(newH);
        }
        function up() {
            $(document).off('mousemove touchmove', move);
            $(document).off('mouseup touchend', up);
        }
        $(document).on('mousemove touchmove', move);
        $(document).on('mouseup touchend', up);
    });

    // ---- Restock alert modal ----
    $(document).on('click', '[data-lebas-restock]', function (e) {
        e.preventDefault();
        var $m = $('#' + $(this).data('lebas-restock'));
        if ($m.length) $m.addClass('is-open');
    });
    $(document).on('click', '.lebas-restock-modal__close, .lebas-restock-modal', function (e) {
        if (e.target === this) $(this).closest('.lebas-restock-modal').removeClass('is-open');
    });
    $(document).on('click', '.lebas-restock-modal__channel', function () {
        $(this).siblings().removeClass('is-active');
        $(this).addClass('is-active');
    });

    // ---- Size concierge: out-of-stock click -> bottom sheet ---------- */
    $(document).on('click', '.lebas-product-card__size.is-unavailable', function () {
        var size = $(this).text().trim();
        var bsId = $(this).closest('.lebas-product-card').data('concierge-bs');
        if (bsId) {
            $('#' + bsId).find('.lebas-bottomsheet__title').text('مدل‌های مشابه در سایز ' + size);
            openBottomSheet(bsId);
        }
    });

    // ---- Cart urgency bar countdown (15 min) ----
    function startUrgencyTimer($bar) {
        var end = parseInt($bar.data('expires'), 10);
        if (!end) return;
        var $timer = $bar.find('.lebas-cart-urgency-bar__timer');
        function tick() {
            var now = Math.floor(Date.now() / 1000);
            var rem = end - now;
            if (rem <= 0) { $bar.remove(); return; }
            var m = Math.floor(rem / 60);
            var s = rem % 60;
            $timer.text(m + ':' + (s < 10 ? '0' : '') + s);
            setTimeout(tick, 1000);
        }
        tick();
    }
    $(function () {
        $('.lebas-cart-urgency-bar').each(function () { startUrgencyTimer($(this)); });
    });

    // ---- Packaging selector ----
    $(document).on('click', '.lebas-packaging__option', function () {
        $(this).siblings().removeClass('is-selected');
        $(this).addClass('is-selected');
        var val = $(this).data('value');
        $('input[name="lebas_packaging_type"]').val(val);
    });

    // ---- Designer directory jump ----
    $(document).on('click', '.lebas-designers__jump a', function (e) {
        e.preventDefault();
        var target = $(this).attr('href');
        var $el = $(target);
        if ($el.length) {
            $('html,body').animate({ scrollTop: $el.offset().top - 80 }, 300);
        }
    });
})(jQuery);