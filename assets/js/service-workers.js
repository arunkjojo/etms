const catchName = "etms";
const cacheAssets = [
  "/classes/admin_class.php",

  "/include/sidebar.php",
  "/include/login_header.php",
  "/include/footer.php",
  "/include/manifest.json",

  "/admin-manage-user.php",
  "/admin-password-change.php",
  "/attendance-info.php",
  "/authentication.php",
  "/changePasswordForEmployee.php",
  "/daily-attendance-report.php",
  "/daily-task-report.php",
  "/edit-task.php",
  "/ems_header.php",
  "/index.php",
  "/leave-info.php",
  "/manage-admin.php",
  "/task-details.php",
  "/task-info.php",
  "/update-admin.php",
  "/update-employee.php",
  "/update-task.php",
  "/auth-lock.php",

  "/assets/bootstrap-datepicker/css/datepicker-custom.css",
  "/assets/bootstrap-datepicker/css/datepicker.css",
  "/assets/bootstrap-datepicker/js/bootstrap-datepicker.js",
  "/assets/bootstrap-datepicker/js/datepicker-custom.js",

  "/assets/css/bootstrap-theme.min.css",
  "/assets/css/bootstrap-theme.min.css.map",
  "/assets/css/bootstrap.min.css",
  "/assets/css/bootstrap.min.css.map",
  "/assets/css/custom.css",
  "/assets/css/responsive.css",

  "/assets/fonts/glyphicons-halflings-regular.eot",
  "/assets/fonts/glyphicons-halflings-regular.svg",
  "/assets/fonts/glyphicons-halflings-regular.ttf",
  "/assets/fonts/glyphicons-halflings-regular.woff",
  "/assets/fonts/glyphicons-halflings-regular.woff2",

  "/assets/img/bg-img.jpg",
  "/assets/img/icon_x192.png",
  "/assets/img/icon_x48.png",
  "/assets/img/icon_x512.png",

  "/assets/js/bootstrap.min.js",
  "/assets/js/custom.js",
  "/assets/js/jquery.min.js",
  "/assets/js/npm.js",
  "/assets/js/service-workers.js",
];

// On install - caching the application shell
self.addEventListener("install", function (event) {
  console.log("Service Worker: Installed");
  event.waitUntil(
    caches
      .open(catchName)
      .then((cache) => {
        // cache any static files that make up the application shell
        console.log("Service Worker: caching Files");
        cache.addAll(cacheAssets);
      })
      .then(() => self.skipWaiting())
  );
});

// On install - caching the application shell
self.addEventListener("activate", (e) => {
  console.log("Service Worker: Activated");
  e.waitUntil(
    caches.keys().then((catchNames) => {
      return Promise.all(
        catchNames.map((cache) => {
          if (cache !== catchName) {
            console.log("Service Worker: Clearing Old Cache");
            return caches.delete(cache);
          }
        })
      );
    })
  );
});

// On network request
self.addEventListener("fetch", function (event) {
  event.respondWith(
    // Try the cache
    caches.match(event.request).then(function (response) {
      //If response found return it, else fetch again
      return response || fetch(event.request);
    })
  );
});
