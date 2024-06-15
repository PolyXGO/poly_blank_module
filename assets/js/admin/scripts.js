(function ($) {
    "use strict";
    console.log(`Poly Blank Demo: js/admin/scripts.js`);
    const moduleSlug = "poly_blank_module";
    const menuMapping = {
        "poly_blank_module/settings_tab": {
            main: '.menu-item-poly_blank_module',
            sub: '.sub-menu-item-settings_tab'
        }
    };

    PolyBlankModuleOperationFunctions.ActiveMenu(moduleSlug, menuMapping);
})(jQuery);