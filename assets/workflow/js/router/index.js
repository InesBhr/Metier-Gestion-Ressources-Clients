export const routes = [
  {
    path: "/workflow/anomalies",
    name: "anomalies",
    component: () => import("/assets/workflow/js/views/Anomalies.vue"),
    props: { userPermissions: userPermissions },
  },
  {
    path: "/workflow/rejects",
    name: "rejects",
    component: () => import("/assets/workflow/js/views/Rejects.vue"),
    props: { userPermissions: userPermissions },
  },
];
