import { createRouter, createWebHistory } from "vue-router";
// import { routes as DocumentsRoutes } from "../../../documentation/js/router";
// import { routes as FilesAccessRoutes } from "../../../files-access/js/router";
import { routes as WorkflowRoutes } from "../../../workflow/js/router";

export const HomeRoutes = [
  {
    path: "/",
    name: "home",
    component: () => import("/assets/core/js/views/HomeCards.vue"),
  },
];
const routes = HomeRoutes.concat(WorkflowRoutes);

const router = createRouter({
  history: createWebHistory(),
  routes,
  linkActiveClass: "vsm-selected",
});

export default router;
