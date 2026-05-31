import PerfectScrollbar from "perfect-scrollbar";
import { SidebarSkeleton  } from "sidebar-skeleton-compostrap";
import { SidebarMenuApp } from "sidebar-menu-compostrap";

export function initAdminTheme() {
	SidebarSkeleton.init();
	SidebarMenuApp.init();
	initScrollbar('.scrollbar');
}

/* Function to initialize PerfectScrollbar */
function initScrollbar(selector) {
	new PerfectScrollbar(selector, { wheelSpeed: 0.3 });
}
