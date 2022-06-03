/*=========================================================================================
  File Name: themeConfig.js
  Description: Theme configuration
  ----------------------------------------------------------------------------------------
  Item Name: Modern Admin - Clean Bootstrap 4 Dashboard HTML Template
  Author: Pixinvent
  Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/
const color = require("@/assets/less/colors.js");

// MAIN COLORS - VUESAX THEME COLORS
let colors = {
	primary: color.primary,
	success: color.success,
	danger: color.error,
	warning: color.warning,
	dark: color.black,
}

import Vue from 'vue'
import Vuesax from 'vuesax'
Vue.use(Vuesax, { theme:{ colors } });

// CONFIGS
const themeConfig = {
	theme: 'light',						// options[String]: 'light'(default), 'dark', 'semi-dark'
	sidebarCollapsed: false,			// options[Boolean]: true, false(default)
	navbarColor: "#fff",				// options[String]: HEX color / rgb / rgba / Valid HTML Color name - (default: #fff)
	navbarType: "sticky",				// options[String]: floating(default) / static / sticky / hidden
	footerType: "static",				// options[String]: static(default) / sticky / hidden
	routerTransition: 'zoom-fade',		// options[String]: zoom-fade / slide-fade / fade-bottom / fade / zoom-out / none(default)
	disableCustomizer: false,			// options[Boolean]: true, false(default)
	hideScrollToTop: false,				// options[Boolean]: true, false(default)
	disableThemeTour: false,					// options[Boolean]: true, false(default)
	colors:colors
  // NOTE: themeTour will be disabled in screens < 1200. Please refer docs for more info.
}

export default themeConfig
