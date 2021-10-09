'use strict';

async function saveSettings(key, value, state) {
    let data = {
        key: key,
        value: value,
        state: state,
        '_csrf-backend': $('meta[name=csrf-token]').prop('content'),
    }

    const response = await fetch('/site/settings/', {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        mode: 'cors', // no-cors, *cors, same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, *same-origin, omit
        headers: {
            'Content-Type': 'application/json'
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        redirect: 'follow', // manual, *follow, error
        referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
        body: JSON.stringify(data) // body data type must match "Content-Type" header
    });
    return response.json();
}

(function ($) {

    // Color Arrays

    const navbar_dark_skins = [
        'navbar-primary',
        'navbar-secondary',
        'navbar-info',
        'navbar-success',
        'navbar-danger',
        'navbar-indigo',
        'navbar-purple',
        'navbar-pink',
        'navbar-navy',
        'navbar-lightblue',
        'navbar-teal',
        'navbar-cyan',
        'navbar-dark',
        'navbar-gray-dark',
        'navbar-gray'
    ];

    const navbar_light_skins = [
        'navbar-light',
        'navbar-warning',
        'navbar-white',
        'navbar-orange'
    ];

    const sidebar_colors = [
        'bg-primary',
        'bg-warning',
        'bg-info',
        'bg-danger',
        'bg-success',
        'bg-indigo',
        'bg-lightblue',
        'bg-navy',
        'bg-purple',
        'bg-fuchsia',
        'bg-pink',
        'bg-maroon',
        'bg-orange',
        'bg-lime',
        'bg-teal',
        'bg-olive'
    ];

    const accent_colors = [
        'accent-primary',
        'accent-warning',
        'accent-info',
        'accent-danger',
        'accent-success',
        'accent-indigo',
        'accent-lightblue',
        'accent-navy',
        'accent-purple',
        'accent-fuchsia',
        'accent-pink',
        'accent-maroon',
        'accent-orange',
        'accent-lime',
        'accent-teal',
        'accent-olive'
    ];

    const sidebar_skins = [
        'sidebar-dark-primary',
        'sidebar-dark-warning',
        'sidebar-dark-info',
        'sidebar-dark-danger',
        'sidebar-dark-success',
        'sidebar-dark-indigo',
        'sidebar-dark-lightblue',
        'sidebar-dark-navy',
        'sidebar-dark-purple',
        'sidebar-dark-fuchsia',
        'sidebar-dark-pink',
        'sidebar-dark-maroon',
        'sidebar-dark-orange',
        'sidebar-dark-lime',
        'sidebar-dark-teal',
        'sidebar-dark-olive',
        'sidebar-light-primary',
        'sidebar-light-warning',
        'sidebar-light-info',
        'sidebar-light-danger',
        'sidebar-light-success',
        'sidebar-light-indigo',
        'sidebar-light-lightblue',
        'sidebar-light-navy',
        'sidebar-light-purple',
        'sidebar-light-fuchsia',
        'sidebar-light-pink',
        'sidebar-light-maroon',
        'sidebar-light-orange',
        'sidebar-light-lime',
        'sidebar-light-teal',
        'sidebar-light-olive'
    ];

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1)
    }

    function createSkinBlock(colors, noneSelected) {
        const $block = $('<select />', {
            class: noneSelected ? 'custom-select mb-3 border-0' : 'custom-select mb-3 text-light border-0 ' + colors[0].replace(/accent-|navbar-/, 'bg-')
        });

        if (noneSelected) {
            const $default = $('<option />', {
                text: 'None Selected'
            });

            $block.append($default)
        }

        colors.forEach(function (color) {
            const $color = $('<option />', {
                class: (typeof color === 'object' ? color.join(' ') : color).replace('navbar-', 'bg-').replace('accent-', 'bg-'),
                text: capitalizeFirstLetter((typeof color === 'object' ? color.join(' ') : color).replace(/navbar-|accent-|bg-/, '').replace('-', ' '))
            });

            $color.data('color', color)
            $block.append($color);

        })
        return $block
    }


    const $sidebar_light_variants = createSkinBlock(sidebar_colors, true);
    $sidebar_light_variants.on('change', function () {
        const color = $(this).find(":selected").data('color');
        const sidebar_class = 'sidebar-light-' + color.replace('bg-', '');
        const $sidebar = $('.main-sidebar');
        sidebar_skins.forEach(function (skin) {
            $sidebar.removeClass(skin)
            $sidebar_dark_variants.removeClass(skin.replace('sidebar-light-', 'bg-')).removeClass('text-light')
        })

        $(this).parent().removeClass().addClass('custom-select mb-3 text-light border-0').addClass(color)

        $sidebar_dark_variants.find('option').prop('selected', false)
        $sidebar.addClass(sidebar_class)
        $('.sidebar').removeClass('os-theme-light').addClass('os-theme-dark')

        saveSettings('theme|color.sidebar', 'light-' + color.replace('bg-', ''), null).then(r => r);
    });
    const $sidebar = $('.control-sidebar');
    const $container = $('<div />', {
        class: 'p-3 control-sidebar-content'
    });

    $sidebar.append($container)

    // Checkboxes

    $container.append(
        '<h5>Customize AdminLTE</h5><hr class="mb-2"/>'
    )

    const $dark_mode_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('dark-mode'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('body').addClass('dark-mode')
            saveSettings('theme|body', 'dark-mode', true);
        } else {
            $('body').removeClass('dark-mode')
            saveSettings('theme|body', 'dark-mode', false);
        }
    });
    const $dark_mode_container = $('<div />', {class: 'mb-4'}).append($dark_mode_checkbox).append('<span>Dark Mode</span>');
    $container.append($dark_mode_container)

    $container.append('<h6>Header Options</h6>')
    const $header_fixed_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('layout-navbar-fixed'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('body').addClass('layout-navbar-fixed')
            saveSettings('theme|body', 'layout-navbar-fixed', true);
        } else {
            $('body').removeClass('layout-navbar-fixed')
            saveSettings('theme|body', 'layout-navbar-fixed', false);
        }
    });
    const $header_fixed_container = $('<div />', {class: 'mb-1'}).append($header_fixed_checkbox).append('<span>Fixed</span>');
    $container.append($header_fixed_container)

    const $dropdown_legacy_offset_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.main-header').hasClass('dropdown-legacy'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('.main-header').addClass('dropdown-legacy')
            saveSettings('theme|.main-header', 'dropdown-legacy', true);
        } else {
            $('.main-header').removeClass('dropdown-legacy')
            saveSettings('theme|.main-header', 'dropdown-legacy', false);
        }
    });
    const $dropdown_legacy_offset_container = $('<div />', {class: 'mb-1'}).append($dropdown_legacy_offset_checkbox).append('<span>Dropdown Legacy Offset</span>');
    $container.append($dropdown_legacy_offset_container)

    const $no_border_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.main-header').hasClass('border-bottom-0'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('.main-header').addClass('border-bottom-0')
            saveSettings('theme|.main-header', 'border-bottom-0', true);
        } else {
            $('.main-header').removeClass('border-bottom-0')
            saveSettings('theme|.main-header', 'border-bottom-0', false);
        }
    });
    const $no_border_container = $('<div />', {class: 'mb-4'}).append($no_border_checkbox).append('<span>No border</span>');
    $container.append($no_border_container)

    $container.append('<h6>Sidebar Options</h6>')

    const $sidebar_collapsed_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('sidebar-collapse'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('body').addClass('sidebar-collapse')
            $(window).trigger('resize')
            saveSettings('theme|body', 'sidebar-collapse', true);
        } else {
            $('body').removeClass('sidebar-collapse')
            $(window).trigger('resize')
            saveSettings('theme|body', 'sidebar-collapse', false);
        }
    });
    const $sidebar_collapsed_container = $('<div />', {class: 'mb-1'}).append($sidebar_collapsed_checkbox).append('<span>Collapsed</span>');
    $container.append($sidebar_collapsed_container)

    $(document).on('collapsed.lte.pushmenu', '[data-widget="pushmenu"]', function () {
        $sidebar_collapsed_checkbox.prop('checked', true)
    })
    $(document).on('shown.lte.pushmenu', '[data-widget="pushmenu"]', function () {
        $sidebar_collapsed_checkbox.prop('checked', false)
    })

    const $sidebar_fixed_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('layout-fixed'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('body').addClass('layout-fixed')
            $(window).trigger('resize')
            saveSettings('theme|body', 'layout-fixed', true);
        } else {
            $('body').removeClass('layout-fixed')
            $(window).trigger('resize')
            saveSettings('theme|body', 'layout-fixed', false);
        }
    });
    const $sidebar_fixed_container = $('<div />', {class: 'mb-1'}).append($sidebar_fixed_checkbox).append('<span>Fixed</span>');
    $container.append($sidebar_fixed_container)

    const $sidebar_mini_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('sidebar-mini'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('body').addClass('sidebar-mini')
            saveSettings('theme|body', 'sidebar-mini', true);
        } else {
            $('body').removeClass('sidebar-mini')
            saveSettings('theme|body', 'sidebar-mini', false);
        }
    });
    const $sidebar_mini_container = $('<div />', {class: 'mb-1'}).append($sidebar_mini_checkbox).append('<span>Sidebar Mini</span>');
    $container.append($sidebar_mini_container)

    const $sidebar_mini_md_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('sidebar-mini-md'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('body').addClass('sidebar-mini-md')
            saveSettings('theme|body', 'sidebar-mini-md', true);
        } else {
            $('body').removeClass('sidebar-mini-md')
            saveSettings('theme|body', 'sidebar-mini-md', false);
        }
    });
    const $sidebar_mini_md_container = $('<div />', {class: 'mb-1'}).append($sidebar_mini_md_checkbox).append('<span>Sidebar Mini MD</span>');
    $container.append($sidebar_mini_md_container)

    const $sidebar_mini_xs_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('sidebar-mini-xs'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('body').addClass('sidebar-mini-xs')
            saveSettings('theme|body', 'sidebar-mini-xs', true);
        } else {
            $('body').removeClass('sidebar-mini-xs')
            saveSettings('theme|body', 'sidebar-mini-xs', false);
        }
    });
    const $sidebar_mini_xs_container = $('<div />', {class: 'mb-1'}).append($sidebar_mini_xs_checkbox).append('<span>Sidebar Mini XS</span>');
    $container.append($sidebar_mini_xs_container)

    const $flat_sidebar_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.nav-sidebar').hasClass('nav-flat'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('.nav-sidebar').addClass('nav-flat')
            saveSettings('theme|.nav-sidebar', 'nav-flat', true);
        } else {
            $('.nav-sidebar').removeClass('nav-flat')
            saveSettings('theme|.nav-sidebar', 'nav-flat', false);
        }
    });
    const $flat_sidebar_container = $('<div />', {class: 'mb-1'}).append($flat_sidebar_checkbox).append('<span>Nav Flat Style</span>');
    $container.append($flat_sidebar_container)

    const $legacy_sidebar_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.nav-sidebar').hasClass('nav-legacy'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('.nav-sidebar').addClass('nav-legacy')
            saveSettings('theme|.nav-sidebar', 'nav-legacy', true);
        } else {
            $('.nav-sidebar').removeClass('nav-legacy')
            saveSettings('theme|.nav-sidebar', 'nav-legacy', false);
        }
    });
    const $legacy_sidebar_container = $('<div />', {class: 'mb-1'}).append($legacy_sidebar_checkbox).append('<span>Nav Legacy Style</span>');
    $container.append($legacy_sidebar_container)

    const $compact_sidebar_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.nav-sidebar').hasClass('nav-compact'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('.nav-sidebar').addClass('nav-compact')
            saveSettings('theme|.nav-sidebar', 'nav-compact', true);
        } else {
            $('.nav-sidebar').removeClass('nav-compact')
            saveSettings('theme|.nav-sidebar', 'nav-compact', false);
        }
    });
    const $compact_sidebar_container = $('<div />', {class: 'mb-1'}).append($compact_sidebar_checkbox).append('<span>Nav Compact</span>');
    $container.append($compact_sidebar_container)

    const $child_indent_sidebar_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.nav-sidebar').hasClass('nav-child-indent'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('.nav-sidebar').addClass('nav-child-indent')
            saveSettings('theme|.nav-sidebar', 'nav-child-indent', true);
        } else {
            $('.nav-sidebar').removeClass('nav-child-indent')
            saveSettings('theme|.nav-sidebar', 'nav-child-indent', false);
        }
    });
    const $child_indent_sidebar_container = $('<div />', {class: 'mb-1'}).append($child_indent_sidebar_checkbox).append('<span>Nav Child Indent</span>');
    $container.append($child_indent_sidebar_container)

    const $child_hide_sidebar_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.nav-sidebar').hasClass('nav-collapse-hide-child'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('.nav-sidebar').addClass('nav-collapse-hide-child')
            saveSettings('theme|.nav-sidebar', 'nav-collapse-hide-child', true);
        } else {
            $('.nav-sidebar').removeClass('nav-collapse-hide-child')
            saveSettings('theme|.nav-sidebar', 'nav-collapse-hide-child', false);
        }
    });
    const $child_hide_sidebar_container = $('<div />', {class: 'mb-1'}).append($child_hide_sidebar_checkbox).append('<span>Nav Child Hide on Collapse</span>');
    $container.append($child_hide_sidebar_container)

    const $no_expand_sidebar_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.main-sidebar').hasClass('sidebar-no-expand'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('.main-sidebar').addClass('sidebar-no-expand')
            saveSettings('theme|.main-sidebar', 'sidebar-no-expand', true);
        } else {
            $('.main-sidebar').removeClass('sidebar-no-expand')
            saveSettings('theme|.main-sidebar', 'sidebar-no-expand', false);
        }
    });
    const $no_expand_sidebar_container = $('<div />', {class: 'mb-4'}).append($no_expand_sidebar_checkbox).append('<span>Disable Hover/Focus Auto-Expand</span>');
    $container.append($no_expand_sidebar_container)

    $container.append('<h6>Footer Options</h6>')
    const $footer_fixed_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('layout-footer-fixed'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('body').addClass('layout-footer-fixed')
            saveSettings('theme|body', 'layout-footer-fixed', true);
        } else {
            $('body').removeClass('layout-footer-fixed')
            saveSettings('theme|body', 'layout-footer-fixed', false);
        }
    });
    const $footer_fixed_container = $('<div />', {class: 'mb-4'}).append($footer_fixed_checkbox).append('<span>Fixed</span>');
    $container.append($footer_fixed_container)

    $container.append('<h6>Small Text Options</h6>')

    const $text_sm_body_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('text-sm'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('body').addClass('text-sm')
            saveSettings('theme|body', 'text-sm', true);
        } else {
            $('body').removeClass('text-sm')
            saveSettings('theme|body', 'text-sm', false);
        }
    });
    const $text_sm_body_container = $('<div />', {class: 'mb-1'}).append($text_sm_body_checkbox).append('<span>Body</span>');
    $container.append($text_sm_body_container)

    const $text_sm_header_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.main-header').hasClass('text-sm'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('.main-header').addClass('text-sm')
            saveSettings('theme|.main-header', 'text-sm', true);
        } else {
            $('.main-header').removeClass('text-sm')
            saveSettings('theme|.main-header', 'text-sm', false);
        }
    });
    const $text_sm_header_container = $('<div />', {class: 'mb-1'}).append($text_sm_header_checkbox).append('<span>Navbar</span>');
    $container.append($text_sm_header_container)

    const $text_sm_brand_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.brand-link').hasClass('text-sm'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('.brand-link').addClass('text-sm')
            saveSettings('theme|.brand-link', 'text-sm', true);
        } else {
            $('.brand-link').removeClass('text-sm')
            saveSettings('theme|.brand-link', 'text-sm', false);
        }
    });
    const $text_sm_brand_container = $('<div />', {class: 'mb-1'}).append($text_sm_brand_checkbox).append('<span>Brand</span>');
    $container.append($text_sm_brand_container)

    const $text_sm_sidebar_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.nav-sidebar').hasClass('text-sm'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('.nav-sidebar').addClass('text-sm')
            saveSettings('theme|.nav-sidebar', 'text-sm', true);
        } else {
            $('.nav-sidebar').removeClass('text-sm')
            saveSettings('theme|.nav-sidebar', 'text-sm', false);
        }
    });
    const $text_sm_sidebar_container = $('<div />', {class: 'mb-1'}).append($text_sm_sidebar_checkbox).append('<span>Sidebar Nav</span>');
    $container.append($text_sm_sidebar_container)

    const $text_sm_footer_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.main-footer').hasClass('text-sm'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('.main-footer').addClass('text-sm')
            saveSettings('theme|.main-footer', 'text-sm', true);
        } else {
            $('.main-footer').removeClass('text-sm')
            saveSettings('theme|.main-footer', 'text-sm', false);
        }
    });
    const $text_sm_footer_container = $('<div />', {class: 'mb-4'}).append($text_sm_footer_checkbox).append('<span>Footer</span>');
    $container.append($text_sm_footer_container)

    // Navbar Variants

    $container.append('<h6>Navbar Variants</h6>')

    const $navbar_variants = $('<div />', {
        class: 'd-flex'
    });
    const navbar_all_colors = navbar_dark_skins.concat(navbar_light_skins);
    const $navbar_variants_colors = createSkinBlock(navbar_all_colors);
    $navbar_variants_colors.on('change', function () {
        const color = $(this).find(':selected').data('color');
        const $main_header = $('.main-header');
        $main_header.removeClass('navbar-dark').removeClass('navbar-light')
        navbar_all_colors.forEach(function (color) {
            $main_header.removeClass(color)
        })

        $(this).parent().removeClass().addClass('custom-select mb-3 text-light border-0 ')

        if (navbar_dark_skins.indexOf(color) > -1) {
            $main_header.addClass('navbar-dark')
            $(this).parent().addClass(color).addClass('text-light')
        } else {
            $main_header.addClass('navbar-light')
            $(this).parent().addClass(color)
        }

        $main_header.addClass(color)

        saveSettings('theme|color.navbar', color, null).then(r => r);
    });

    let active_navbar_color = null;
    $('.main-header')[0].classList.forEach(function (className) {
        if (navbar_all_colors.indexOf(className) > -1 && active_navbar_color === null) {
            active_navbar_color = className.replace('navbar-', 'bg-')
        }
    })

    $navbar_variants_colors.find('option.' + active_navbar_color).prop('selected', true)
    $navbar_variants_colors.removeClass().addClass('custom-select mb-3 text-light border-0 ').addClass(active_navbar_color)

    $navbar_variants.append($navbar_variants_colors)

    $container.append($navbar_variants)

    // Sidebar Colors

    $container.append('<h6>Accent Color Variants</h6>')
    const $accent_variants = $('<div />', {
        class: 'd-flex'
    });
    $container.append($accent_variants)
    const $accesnt_variants_select = createSkinBlock(accent_colors, true)
    $accesnt_variants_select.on('change', function () {
        const color = $(this).find(':selected').data('color');
        const accent_class = color;
        const $body = $('body');
        accent_colors.forEach(function (skin) {
            $body.removeClass(skin)
        })

        $body.addClass(accent_class)
        saveSettings('theme|color.accent', color, null).then(r => r);
    })

    $container.append($accesnt_variants_select)
    let active_accent_color = null;
    $('body')[0].classList.forEach(function (className) {
        if (accent_colors.indexOf(className) > -1 && active_accent_color === null) {
            active_accent_color = className.replace('navbar-', 'bg-')
        }
    })

    // $accent_variants.find('option.' + active_accent_color).prop('selected', true)
    // $accent_variants.removeClass().addClass('custom-select mb-3 text-light border-0 ').addClass(active_accent_color)

    $container.append('<h6>Dark Sidebar Variants</h6>')
    const $sidebar_variants_dark = $('<div />', {
        class: 'd-flex'
    });
    $container.append($sidebar_variants_dark)
    const $sidebar_dark_variants = createSkinBlock(sidebar_colors, true);
    $sidebar_dark_variants.on('change', function () {
        const color = $(this).find(':selected').data('color');
        const sidebar_class = 'sidebar-dark-' + color.replace('bg-', '');
        const $sidebar = $('.main-sidebar');
        sidebar_skins.forEach(function (skin) {
            $sidebar.removeClass(skin)
            $sidebar_light_variants.removeClass(skin.replace('sidebar-dark-', 'bg-')).removeClass('text-light')
        })

        $(this).parent().removeClass().addClass('custom-select mb-3 text-light border-0').addClass(color)

        $sidebar_light_variants.find('option').prop('selected', false)
        $sidebar.addClass(sidebar_class)
        $('.sidebar').removeClass('os-theme-dark').addClass('os-theme-light')
        saveSettings('theme|color.sidebar', 'dark-' + color.replace('bg-', ''), null).then(r => r);
    })
    $container.append($sidebar_dark_variants)

    let active_sidebar_dark_color = null;
    $('.main-sidebar')[0].classList.forEach(function (className) {
        const color = className.replace('sidebar-dark-', 'bg-');
        if (sidebar_colors.indexOf(color) > -1 && active_sidebar_dark_color === null) {
            active_sidebar_dark_color = color
        }
    })

    $sidebar_dark_variants.find('option.' + active_sidebar_dark_color).prop('selected', true)
    $sidebar_dark_variants.removeClass().addClass('custom-select mb-3 text-light border-0 ').addClass(active_sidebar_dark_color)

    $container.append('<h6>Light Sidebar Variants</h6>')
    const $sidebar_variants_light = $('<div />', {
        class: 'd-flex'
    });
    $container.append($sidebar_variants_light)
    $container.append($sidebar_light_variants)

    let active_sidebar_light_color = null;
    $('.main-sidebar')[0].classList.forEach(function (className) {
        const color = className.replace('sidebar-light-', 'bg-');
        if (sidebar_colors.indexOf(color) > -1 && active_sidebar_light_color === null) {
            active_sidebar_light_color = color
        }
    })

    if (active_sidebar_light_color !== null) {
        $sidebar_light_variants.find('option.' + active_sidebar_light_color).prop('selected', true)
        $sidebar_light_variants.removeClass().addClass('custom-select mb-3 text-light border-0 ').addClass(active_sidebar_light_color)
    }

    const logo_skins = navbar_all_colors;
    $container.append('<h6>Brand Logo Variants</h6>')
    const $logo_variants = $('<div />', {
        class: 'd-flex'
    });
    $container.append($logo_variants)
    const $clear_btn = $('<a />', {
        href: '#'
    }).text('clear').on('click', function (e) {
        e.preventDefault()
        const $logo = $('.brand-link');
        logo_skins.forEach(function (skin) {
            $logo.removeClass(skin)
        })
    });

    const $brand_variants = createSkinBlock(logo_skins, true).append($clear_btn);
    $brand_variants.on('change', function () {
        let color = $(this).find(':selected').data('color')
        let $logo = $('.brand-link')

        if (color === 'navbar-light' || color === 'navbar-white') {
            $logo.addClass('text-black')
        } else {
            $logo.removeClass('text-black')
        }

        logo_skins.forEach(function (skin) {
            $logo.removeClass(skin)
        })

        if (color) {
            $(this).parent().removeClass().addClass('custom-select mb-3 border-0').addClass(color).addClass(color !== 'navbar-light' && color !== 'navbar-white' ? 'text-light' : '')
        } else {
            $(this).parent().removeClass().addClass('custom-select mb-3 border-0')
        }

        $logo.addClass(color)
        saveSettings('theme|color.brandlink', color, null).then(r => r);
    });
    $container.append($brand_variants)

    let active_brand_color = null;
    $('.brand-link')[0].classList.forEach(function (className) {
        if (logo_skins.indexOf(className) > -1 && active_brand_color === null) {
            active_brand_color = className.replace('navbar-', 'bg-')
        }
    })

    if (active_brand_color) {
        $brand_variants.find('option.' + active_brand_color).prop('selected', true)
        $brand_variants.removeClass().addClass('custom-select mb-3 text-light border-0 ').addClass(active_brand_color)
    }
})(jQuery)
