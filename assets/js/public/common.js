class PolyBlankModuleOperationFunctions {
    static ActiveMenu = (slug, menuMapping) => {
        const _url = window.location.href;

        Object.keys(menuMapping).forEach(key => {
            if (_url.includes(key)) {
                $(menuMapping[key].main).addClass('active');
                if (menuMapping[key].sub) {
                    $(menuMapping[key].sub).addClass('active');
                }
            }
        });
    }
}
window.PolyBlankModuleOperationFunctions = PolyBlankModuleOperationFunctions;